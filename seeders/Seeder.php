<?php

namespace Seeders;

use Cycle\Database\DatabaseManager;

require_once 'config/env.php';

class Seeder
{
    protected $dbal;

    public function __construct()
    {
        global $dbal; // Ensure $dbal is accessible
        $this->dbal = $dbal;
    }

    protected function insert(string $table, array $data)
    {
        // Use Cycle ORM's insert query builder
        $this->dbal->database('default')->table($table)->insertOne($data);
    }
}
