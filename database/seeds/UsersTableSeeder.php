<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Fendy Candra Nugraha',
            'email' => 'fendysketsa@gmail.com',
            'password' => bcrypt('fendy12345'),
        ]);
    }
}