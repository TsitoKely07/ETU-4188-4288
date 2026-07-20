<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Préfixes
        $this->db->table('prefixe')->insertBatch([
            ['code' => '033'],
            ['code' => '037'],
        ]);

        // 2. Types d'opérations
        $this->db->table('type_operation')->insertBatch([
            ['id' => 1, 'nom' => 'depot'],
            ['id' => 2, 'nom' => 'retrait'],
            ['id' => 3, 'nom' => 'transfert'],
        ]);

        // 3. Barèmes de frais
        $this->db->table('bareme_frais')->insertBatch([
            ['id_type_operation' => 2, 'montant_min' => 1000.0, 'montant_max' => 10000.0, 'frais' => 200.0],
            ['id_type_operation' => 2, 'montant_min' => 10001.0, 'montant_max' => 50000.0, 'frais' => 500.0],
            ['id_type_operation' => 3, 'montant_min' => 1000.0, 'montant_max' => 10000.0, 'frais' => 100.0],
            ['id_type_operation' => 3, 'montant_min' => 10001.0, 'montant_max' => 50000.0, 'frais' => 250.0],
        ]);
    }
}