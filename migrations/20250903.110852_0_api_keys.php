<?php

use Cycle\Migrations\Migration;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use Cycle\Schema\Definition\Table;

class Api_keysMigration extends Migration
{
    public function up()
{
    $this->table('api_keys')
        ->addColumn('id', 'primary')
        ->addColumn('userId', 'int', ['length' => 11])
        ->addColumn('token', 'string', ['length' => 100])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'])
        ->create();
}

    public function down()
{
    $this->table('api_keys')->drop();
}
}
