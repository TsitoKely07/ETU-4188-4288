<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHistoriqueOperationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'id_compte_source' => [
                'type' => 'INTEGER',
            ],
            'id_compte_dest' => [
                'type'     => 'INTEGER',
                'null'     => true,
            ],
            'id_type_operation' => [
                'type' => 'INTEGER',
            ],
            'montant' => [
                'type' => 'REAL',
            ],
            'frais' => [
                'type'    => 'REAL',
                'default' => 0.0,
            ],
            'id_operateur_destination' => [
                'type'    => 'INTEGER',
                'null'    => true,
                'default' => null,
            ],
            'frais_retrait_inclus' => [
                'type'    => 'INTEGER',
                'default' => 0,
            ],
            'montants_destinations' => [
                'type'    => 'TEXT',
                'null'    => true,
                'default' => null,
            ],
            'transaction_parent' => [
                'type'    => 'INTEGER',
                'null'    => true,
                'default' => null,
            ],
            'date_operation' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_compte_source', 'compte_client', 'id');
        $this->forge->addForeignKey('id_compte_dest', 'compte_client', 'id');
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id');
        $this->forge->addForeignKey('id_operateur_destination', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('historique_operation');
    }

    public function down()
    {
        $this->forge->dropTable('historique_operation');
    }
}
