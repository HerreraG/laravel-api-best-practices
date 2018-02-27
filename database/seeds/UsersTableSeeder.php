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
        /* Super Admin */
        factory(\App\Entities\User::class)->create([
            'name' => 'Gonzalo',
            'email' => 'gonzah@helloworld.com',
            'password' => bcrypt('123456'),
            'active' => 1,
            'remember_token' => str_random(10),
        ]);

        factory(\App\Entities\User::class, 6)->create([
            'password' => bcrypt('123456'),
            'active' => 1,
            'remember_token' => str_random(10),
        ]);

        factory(\App\Entities\UserProfile::class)->create([
            'profile_id' => 1,
            'user_id' => 1
        ]);
    }
}
