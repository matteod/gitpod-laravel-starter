<?php

namespace Database\Seeders;

use App\Models\EditorialProject;
use Illuminate\Database\Seeder;

class EditorialProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EditorialProject::factory()->count(1000)->create();
    }
}