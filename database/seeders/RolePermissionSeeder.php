<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // intialize roles
        $admin = Role::firstWhere('name', 'admin');
        $supervisor = Role::firstWhere('name', 'supervisor');
        $staff = Role::firstWhere('name', 'staff');

        // admin all permissions
        $admin->syncPermissions(Permission::all());

        // supervisor permissions
        $supervisor->syncPermissions([
            // View Permissions
            'dashboard.view',
            'profile.view',
            'item-category.view',
            'item.view',
            'item.deleted.view',
            'inventory-transaction.view',
            'inventory-transaction.deleted.view',
            'item-transaction.view',
            'item-transaction.deleted.view',

            // Update profile
            'profile.update',

            // CRUD Permissions
            'item.create',
            'item.update',
            'item.delete',
            'item.restore',
            'item.force-delete',

            'inventory-transaction.create',
            'inventory-transaction.delete',
            'inventory-transaction.restore',
            'inventory-transaction.force-delete',

            'item-transaction.create',
            'item-transaction.delete',
            'item-transaction.restore',
            'item-transaction.force-delete',

            'distribution.view',
            'distribution.create',
            'distribution.update',
            'distribution.delete',
            'distribution.approval',

            'distribution-item.view',
            'distribution-item.create',
            'distribution-item.delete',
        ]);

        // staff permissions
        $staff->syncPermissions([
            // View Permissions
            'dashboard.view',
            'profile.view',
            'item.view',
            'inventory-transaction.view',
            'item-transaction.view',

            // Update profile
            'profile.update',

            // CRUD Permissions
            'item.create',
            'item.update',

            'inventory-transaction.create',
            'inventory-transaction.delete',

            'item-transaction.create',
            'item-transaction.delete',

            'distribution.view',
            'distribution.create',
            'distribution.update',
            'distribution.delete',

            'distribution-item.view',
            'distribution-item.create',
            'distribution-item.delete',
        ]);
    }
}
