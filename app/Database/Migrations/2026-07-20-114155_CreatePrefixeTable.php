<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrefixeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'TEXT',
                'unique'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('prefixe');
    }

    public function down()
    {
        $this->forge->dropTable('prefixe');
    }
}