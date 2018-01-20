<?php

use Illuminate\Database\Seeder;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Entities\Profile::class)->create([
            'name' => 'Super Administrator',
            'tag' => 'SUPER_ADMINISTRATOR',
            'active' => 1
        ]);

        factory(\App\Entities\Profile::class)->create([
            'name' => 'Collaborator',
            'tag' => 'COLLABORATOR',
            'active' => 1
        ]);

        factory(\App\Entities\Profile::class)->create([
            'name' => 'Moderator',
            'tag' => 'MODERATOR',
            'active' => 1
        ]);
    }
}
