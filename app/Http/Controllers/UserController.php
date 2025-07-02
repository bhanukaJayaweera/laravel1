<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\OrderDeletionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import Log facade

class UserController extends Controller
{
    public function stats()
    {
        $stats = [];
        
        if (auth()->user()->can('handle orders')) {
            $stats['new_orders'] = \App\Models\Order::where('status', 'new')->count();
            $stats['pending_approvals'] = \App\Models\OrderDeletionRequest::where('status', 'Updated')->count();
        }
        
        if (auth()->user()->can('handle customers')) {
            $stats['new_customers'] = \App\Models\Customer::where('created_at', '>', now()->subDays(7))->count();
        }
        
        if (auth()->user()->can('handle promotions')) {
            $stats['active_promotions'] = \App\Models\Promotion::where('is_active', 'yes')->count();
        }
        
        return view('dashboard', compact('stats'));
    }
        
    
    public function create()
    {
        return view('auth.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function index()
    {
        $users = User::all();
        return view('auth.index', compact('users'));
    }

    public function edit(User $user)
    {
         $roles = Role::all();
        return view('auth.edit', compact('user','roles'));
    }

   public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'is_active' => 'sometimes|boolean'
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
        ]);

        $oldData = $user->getOriginal();
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->has('is_active')
        ]);

        // Log the update
        Log::info('User updated', [
            'user_id' => $user->id,
            'updater_id' => auth()->id(),
            'changes' => $user->getChanges(),
            'ip' => $request->ip()
        ]);

        return redirect()->route('users.edit', $user)->with('success', 'User details updated successfully!');
    }

   public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'The password field is required.',
            'new_password.min' => 'The password must be at least 8 characters.',
            'new_password.confirmed' => 'The password confirmation does not match.',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Log the password reset
        Log::info('Password reset', [
            'user_id' => $user->id,
            'reset_by' => auth()->id(),
            'ip' => $request->ip()
        ]);

        return redirect()->route('users.edit', $user)->with('success', 'Password reset successfully!');
    }

    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'sometimes|array',
            'roles.*' => 'exists:roles,name'
        ]);

        try {
            // Get current roles for logging
            $currentRoles = $user->getRoleNames()->toArray();
            
            // Sync roles
            $user->syncRoles($request->roles ?? []);
            // $user->syncRoles($request->roles ?? []);
            // $user->syncPermissions($request->permissions ?? []);
   
            // Log the role changes
            Log::channel('user_activity')->info('User roles updated', [
                'user_id' => $user->id,
                'updated_by' => auth()->id(),
                'old_roles' => $currentRoles,
                'new_roles' => $request->roles ?? [],
                'ip' => $request->ip()
            ]);

            return redirect()
                ->route('users.edit', $user)
                ->with('success', 'Roles updated successfully');

        } catch (\Exception $e) {
            Log::channel('user_activity')->error('Role update failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'attempted_roles' => $request->roles
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update roles: ' . $e->getMessage());
        }
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "User {$status} successfully!");
    }
}
