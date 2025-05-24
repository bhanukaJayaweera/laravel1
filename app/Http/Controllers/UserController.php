<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\OrderDeletionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return view('auth.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'sometimes|boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->has('is_active')
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "User {$status} successfully!");
    }
}
