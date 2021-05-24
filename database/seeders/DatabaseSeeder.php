<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(SectorsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(EditorialProjectSeeder::class);
    }
}