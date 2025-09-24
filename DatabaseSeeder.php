<?php

require_once 'seeders/SeederInterface.php';
require_once 'seeders/UsersSeeder.php'; // Include all your seeder files here
require_once 'seeders/ProjectsSeeder.php'; // Include all your seeder files here


use Seeders\SeederInterface;

class DatabaseSeeder
{
    protected $seeders = [
        \Seeders\UsersSeeder::class,
        \Seeders\ProjectsSeeder::class,
        // Add other seeders here
    ];

    public function run()
    {
        foreach ($this->seeders as $seederClass) {
            $seeder = new $seederClass();
            $seeder->run();
        }
    }
}

$seeder = new DatabaseSeeder();
$seeder->run();
echo "Database seeding completed successfully.\n";
