<?php
use Cycle\Migrations\Migration;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use Cycle\Schema\Definition\Table;


class UsersMigration extends Migration
{

    public function up()
{
    $this->table('users')
        ->addColumn('id', 'primary')
        ->addColumn('first_name', 'string', ['length' => 100, 'nullable' => true])
        ->addColumn('last_name', 'string', ['length' => 100, 'nullable' => true])
        ->addColumn('email', 'string', ['length' => 255, 'unique' => true, 'nullable' => true])
        ->addColumn('phone_no', 'string', ['length' => 255, 'unique' => true, 'nullable' => true])
        ->addColumn('password', 'string', ['length' => 255, 'nullable' => true])
        ->addColumn('type', 'enum',  ['values' => ['user', 'admin'], 'default' => 'user'])
        ->addColumn('status', 'enum', ['values' => ['block', 'active'], 'default' => 'active'])
        ->addColumn('verify_code', 'string', ['length' => 50, 'nullable' => true])
        ->addColumn('profile_image', 'string', ['length' => 100, 'nullable' => true])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'])
        ->create();
}

    public function down()
{
    $this->table('users')->drop();
}
}

