<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaremeFraisTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'id_type_operation' => [
                'type' => 'INTEGER',
            ],
            'montant_min' => [
                'type' => 'REAL',
            ],
            'montant_max' => [
                'type' => 'REAL',
            ],
            'frais' => [
                'type' => 'REAL',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bareme_frais');
    }

    public function down()
    {
        $this->forge->dropTable('bareme_frais');
    }
}