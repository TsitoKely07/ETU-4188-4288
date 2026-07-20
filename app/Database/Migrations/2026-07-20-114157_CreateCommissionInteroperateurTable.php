<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommissionInteroperateurTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'id_operateur_source' => [
                'type' => 'INTEGER',
            ],
            'id_operateur_destination' => [
                'type' => 'INTEGER',
            ],
            'pourcentage_commission' => [
                'type'    => 'REAL',
            ],
            'date_creation' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operateur_source', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_operateur_destination', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('commission_interoperateur');
    }

    public function down()
    {
        $this->forge->dropTable('commission_interoperateur');
    }
}

