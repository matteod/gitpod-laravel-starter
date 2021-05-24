<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_ADMIN)->first());

        $user = new User();
        $user->name = 'Amministratore Delegato';
        $user->email = 'ad@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_CEO)->first());

        $user = new User();
        $user->name = 'Direttore vendite';
        $user->email = 'dv@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_SALES_DIRECTOR)->first());

        $user = new User();
        $user->name = 'Direttore editoriale';
        $user->email = 'de@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_EDITORIAL_DIRECTOR)->first());

        $user = new User();
        $user->name = 'Responsabile realizzazione editoriale';
        $user->email = 're@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_EDITORIAL_RESPONSIBLE)->first());

        $user = new User();
        $user->name = 'Responsabile di progettazione';
        $user->email = 'rp@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_EDITORIAL_DESIGN_MANAGER)->first());

        //User::factory()->count(50)->create();
    }
}