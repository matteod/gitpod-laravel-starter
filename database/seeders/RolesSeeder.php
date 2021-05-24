<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = "Admin";
        $role->description = "Admin";
        $role->key = Role::ROLE_ADMIN;
        $role->save();

        $role = new Role();
        $role->name = "Editorial design managers";
        $role->description = "Editorial design managers";
        $role->key = Role::ROLE_EDITORIAL_DESIGN_MANAGER;
        $role->save();

        $role = new Role();
        $role->name = "Editorial responsible";
        $role->description = "Editorial Responsible";
        $role->key = Role::ROLE_EDITORIAL_RESPONSIBLE;
        $role->save();

        $role = new Role();
        $role->name = "Editorial director";
        $role->description = "Editorial director";
        $role->key = Role::ROLE_EDITORIAL_DIRECTOR;
        $role->save();

        $role = new Role();
        $role->name = "Sales director";
        $role->description = "Sales director";
        $role->key = Role::ROLE_SALES_DIRECTOR;
        $role->save();

        $role = new Role();
        $role->name = "CEO";
        $role->description = "CEO";
        $role->key = Role::ROLE_CEO;
        $role->save();
    }
}