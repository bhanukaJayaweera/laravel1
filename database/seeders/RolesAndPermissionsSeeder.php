<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Clear cache
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

         // Create permissions
         $permissions = ['handle customers', 'handle products', 'handle orders', 'approve customers', 'approve products', 'approve orders'];
         foreach ($permissions as $permission) {
             Permission::firstOrCreate(['name' => $permission]);
         }
 
         // Create roles and assign permissions
         $adminRole = Role::firstOrCreate(['name' => 'admin']);
         $entryRole = Role::firstOrCreate(['name' => 'entry']);
         $approveRole = Role::firstOrCreate(['name' => 'approve']);
 
         $adminRole->givePermissionTo(Permission::all());
         $entryRole->givePermissionTo(['handle customers', 'handle products']);
         $approveRole->givePermissionTo(['handle orders','approve customers', 'approve products', 'approve orders']);
         
 
         // Assign roles to users
    try{
        $adminUser = User::where('name', 'chamal')->first(); // You can specify a user or loop through users
        $adminUser->assignRole($adminRole);
        $entryUser = User::where('name', 'nimal')->first(); // You can specify a user or loop through users
        $entryUser->assignRole($entryRole);
        $approveUser = User::where('name', 'saman')->first(); // You can specify a user or loop through users
        $approveUser->assignRole($approveRole);
    } catch (\Exception $e) {
        $this->command->error('Error assigning roles: '.$e->getMessage());
    }

         // Clear cache at end
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    }
}
