<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\ShippingClass;

class DefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'title'     => 'home',
            'slug'      => 'home',
            'content'   => 'This is the home page content.',
        ]);

        ShippingClass::insert([
            ['name' => 'Lightweight', 'description' => 'Items under 500g', 'inside_rate' => 60.00, 'outside_rate' => 120.00],
            ['name' => 'Standard', 'description' => 'Normal size items', 'inside_rate' => 80.00, 'outside_rate' => 150.00],
            ['name' => 'Bulky / Heavy', 'description' => 'Large or heavy items', 'inside_rate' => 150.00, 'outside_rate' => 300.00],
            ['name' => 'Fragile', 'description' => 'Handle with care', 'inside_rate' => 120.00, 'outside_rate' => 250.00],
        ]);

    }
}
