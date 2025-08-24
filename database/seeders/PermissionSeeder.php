<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $permissions = [
            ['name' => 'manage_grouping', 'group' => 'Grouping'],
            ['name' => 'manage_inventory', 'group' => 'Inventory'],
            ['name' => 'manage_inventory_bidding', 'group' => 'Inventory'],
            ['name' => 'manage_orders', 'group' => 'Order'],
            ['name' => 'manage_users', 'group' => 'User'],
            ['name' => 'manage_carousel', 'group' => 'Carousel'],
            ['name' => 'manage_static_pages', 'group' => 'Pages'],
            ['name' => 'manage_blogs', 'group' => 'Blog'],
            ['name' => 'manage_discount_codes', 'group' => 'Discount'],
            ['name' => 'manage_seo', 'group' => 'Seo'],
            ['name' => 'manage_config', 'group' => 'Config'],
            ['name' => 'manage_setting', 'group' => 'Setting'],

            ['name' => 'manage_permissions', 'group' => 'Permissions'],

        ];

        foreach ($permissions as $perm) {
            \App\Models\Permission::firstOrCreate($perm);
        }
    }
}
