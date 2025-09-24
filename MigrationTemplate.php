<?php

use Cycle\Migrations\Migration;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use Cycle\Schema\Definition\Table;

class {MigrationClassName} extends Migration
{
    public function up()
{
    $this->table('{TableName}')
        ->addColumn('id', 'primary')
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'])
        ->create();
}

    public function down()
{
    $this->table('{TableName}')->drop();
}
}
