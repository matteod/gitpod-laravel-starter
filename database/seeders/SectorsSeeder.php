<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sector::create(
            [
                'name' => 'Marketing',
                'key' => 'marketing',
                'description' => 'Marketing sector'
            ],
        );

        Sector::create(
            [
                'name' => 'Finance',
                'key' => 'finance',
                'description' => 'Finance sector'
            ]
        );

        Sector::create(
            [
                'name' => 'Business',
                'key' => 'business',
                'description' => 'Business sector'
            ]
        );

        Sector::create(
            [
                'name' => 'Sport',
                'key' => 'sport',
                'description' => 'Finance sector'
            ]
        );
    }
}