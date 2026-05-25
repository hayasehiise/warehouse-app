<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Page can access permissions
            'dashboard.view',
            'user.view',
            'role.view',
            'item-category.view',
            'item.view',
            'item.deleted.view',
            'inventory-transaction.view',
            'inventory-transaction.deleted.view',
            'item-transaction.view',
            'item-transaction.deleted.view',

            // CRUD Item Category permissions
            'item-category.create',
            'item-category.update',
            'item-category.delete',
            'item-category.restore',
            'item-category.force-delete',

            // CRUD Item permissions
            'item.create',
            'item.update',
            'item.delete',
            'item.restore',
            'item.force-delete',

            // CRUD Inventory Transaction permissions
            'inventory-transaction.create',
            'inventory-transaction.delete',
            'inventory-transaction.restore',
            'inventory-transaction.force-delete',
            'inventory-transaction.approval',

            // CRUD Item Transaction permissions
            'item-transaction.create',
            'item-transaction.delete',
            'item-transaction.restore',
            'item-transaction.force-delete',

            // Distribution permissions
            'distribution.view',
            'distribution.create',
            'distribution.update',
            'distribution.delete',
            'distribution.restore',
            'distribution.force-delete',
            'distribution.approval',

            // Distribution Item permissions
            'distribution-item.view',
            'distribution-item.create',
            'distribution-item.delete',
        ];
        // Loop for initialize permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
