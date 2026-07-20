<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompteClientTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'numero' => [
                'type'       => 'TEXT',
                'unique'     => true,
            ],
            'solde' => [
                'type'    => 'REAL',
                'default' => 0.0,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('compte_client');
    }

    public function down()
    {
        $this->forge->dropTable('compte_client');
    }
}