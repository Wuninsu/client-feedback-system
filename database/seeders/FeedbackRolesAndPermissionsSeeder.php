<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class FeedbackRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Entities in the feedback system
        $entities = [
            'user',
            'guest user',
            'product category',
            'product',
            'feedback category',
            'feedback',
            'feedback response',
            'template',
            'sent log',
            'activity log',
            'report',
        ];

        $actions = ['view', 'create', 'update', 'delete', 'update own', 'delete own'];

        // Create CRUD permissions for all entities
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action $entity"]);
            }
        }

        // Special permissions
        $specialPermissions = ['view role', 'manage setup', 'view permission', 'create role', 'create permission', 'update role', 'update permission', 'assign role', 'assign permission', 'view analytics'];
        foreach ($specialPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create roles
        $sysDev = Role::firstOrCreate(['name' => 'dev']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $client = Role::firstOrCreate(['name' => 'client']);

        // Assign all permissions to super-admin
        $sysDev->syncPermissions(Permission::all());

        // Assign all except special to admin
        $admin->syncPermissions(
            Permission::whereNotIn('name', $specialPermissions)->get()
        );

        // Staff role - limited to viewing and responding to feedback
        $staff->syncPermissions([
            'view feedback',
            'update own feedback response',
            'create feedback response',
            'view user',
            'view product',
            'view product category',
            'view feedback category',
        ]);

        // Client role - view & submit feedback only
        $client->syncPermissions([
            'create feedback',
            'view product',
            'view feedback category',
        ]);

        // Create users and assign roles
        $devUser = User::firstOrCreate(
            ['email' => 'dev@inventures.com'],
            [
                'username' => 'dev',
                'name' => 'System Dev',
                'phone' => '0500000001',
                'password' => Hash::make('test1234'),
                'is_active' => true,
            ]
        );
        $devUser->assignRole($sysDev);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@inventures.com'],
            [
                'username' => 'admin',
                'name' => 'Admin User',
                'phone' => '0500000002',
                'password' => Hash::make('test1234'),
                'is_active' => true,
            ]
        );
        $adminUser->assignRole($admin);

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@inventures.com'],
            [
                'username' => 'staff',
                'name' => 'Staff User',
                'phone' => '0500000003',
                'password' => Hash::make('test1234'),
                'is_active' => true,
            ]
        );
        $staffUser->assignRole($staff);

        $clientUser = User::firstOrCreate(
            ['email' => 'fuseiniabdulhafiz29@gmail.com'],
            [
                'username' => 'wuninsu',
                'name' => 'Abdul-Hafiz',
                'phone' => '0554234794',
                'password' => Hash::make('test1234'),
                'is_active' => true,
            ]
        );
        $clientUser->assignRole($client);
    }
}
