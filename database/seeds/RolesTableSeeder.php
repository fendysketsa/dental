<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Role::create([
            'name' => 'superadmin'
        ]);

        App\Role::create([
            'name' => 'manager'
        ]);

        App\Role::create([
            'name' => 'terapis'
        ]);

        App\Role::create([
            'name' => 'kasir'
        ]);

        App\Role::create([
            'name' => 'owner'
        ]);
    }
}