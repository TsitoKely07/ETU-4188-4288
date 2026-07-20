<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateurTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('operateur');
    }

    public function down()
    {
        $this->forge->dropTable('operateur');
    }
}

