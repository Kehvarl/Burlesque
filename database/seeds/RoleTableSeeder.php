<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role_admin = New Role();
        $role_admin->name = 'admin';
        $role_admin->description = 'An administrator';
        $role_admin->priority = 0;
        $role_admin->save();

        $role_verified = New Role();
        $role_verified->name = 'verified';
        $role_verified->description = 'A verified user';
        $role_verified->priority = 90;
        $role_verified->save();

        $role_user = New Role();
        $role_user->name = 'user';
        $role_user->description = 'Any user';
        $role_user->priority = 100;
        $role_user->save();
    }
}
