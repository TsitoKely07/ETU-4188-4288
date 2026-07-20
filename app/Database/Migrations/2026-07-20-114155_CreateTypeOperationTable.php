<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypeOperationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'TEXT',
                'unique'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('type_operation');
    }

    public function down()
    {
        $this->forge->dropTable('type_operation');
    }
}