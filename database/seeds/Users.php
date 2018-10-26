<?php

use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $user = new \App\Models\User();
         $user->name = 'testuser';
         $user->api_token = 'TkpJe8qr9hjbqPwCHi0n';
         $user->email = 'test@octopuslabs.com';
         $user->save();
    }
}
