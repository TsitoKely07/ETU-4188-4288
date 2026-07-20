<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDecompteOperateurTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'id_operateur' => [
                'type' => 'INTEGER',
            ],
            'mois_annee' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
            ],
            'montant_total_a_envoyer' => [
                'type'    => 'REAL',
                'default' => 0.0,
            ],
            'montant_deja_envoye' => [
                'type'    => 'REAL',
                'default' => 0.0,
            ],
            'statut' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'en_attente',
            ],
            'date_creation' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('decompte_operateur');
    }

    public function down()
    {
        $this->forge->dropTable('decompte_operateur');
    }
}

