<?php

namespace Seeders;
require_once 'Seeder.php';

class UsersSeeder extends Seeder implements SeederInterface
{
    public function run()
    {
        $this->insert('users', [
            'first_name' => 'Admin',
            'last_name' => 'swiftcart',
            'email' => 'admin@swiftcart.com',
            'password' => password_hash('Team@@11@@', PASSWORD_BCRYPT),
            'type'=>'admin',
            'profile_image'=>'default.webp'
        ]);
    }
}
