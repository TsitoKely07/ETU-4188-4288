<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Opérateurs
        $this->db->table('operateur')->insertBatch([
            ['nom' => 'Airtel', 'code' => 'LOC'],
            ['nom' => 'Orange', 'code' => 'OPA'],
        ]);

        // 2. Préfixes (avec association aux opérateurs)
        $this->db->table('prefixe')->insertBatch([
            ['code' => '033', 'id_operateur' => 1],
            ['code' => '037', 'id_operateur' => 1],
            ['code' => '038', 'id_operateur' => 2],
            ['code' => '034', 'id_operateur' => 2],
        ]);

        // 3. Types d'opérations
        $this->db->table('type_operation')->insertBatch([
            ['id' => 1, 'nom' => 'depot'],
            ['id' => 2, 'nom' => 'retrait'],
            ['id' => 3, 'nom' => 'transfert'],
        ]);

        // 4. Barèmes de frais (complets avec type_frais)
        $this->db->table('bareme_frais')->insertBatch([
            // Frais de retrait
            ['id_type_operation' => 2, 'montant_min' => 1000.0, 'montant_max' => 10000.0, 'frais' => 200.0, 'id_operateur' => null, 'type_frais' => 'retrait'],
            ['id_type_operation' => 2, 'montant_min' => 10001.0, 'montant_max' => 50000.0, 'frais' => 500.0, 'id_operateur' => null, 'type_frais' => 'retrait'],
            // Frais de transfert interne
            ['id_type_operation' => 3, 'montant_min' => 1000.0, 'montant_max' => 10000.0, 'frais' => 100.0, 'id_operateur' => null, 'type_frais' => 'transfert_interne'],
            ['id_type_operation' => 3, 'montant_min' => 10001.0, 'montant_max' => 50000.0, 'frais' => 250.0, 'id_operateur' => null, 'type_frais' => 'transfert_interne'],
        ]);

        // 5. Commission inter-opérateur (Airtel -> Orange : 5%)
        $this->db->table('commission_interoperateur')->insert([
            'id_operateur_source'      => 1,
            'id_operateur_destination' => 2,
            'pourcentage_commission'   => 5.0,
        ]);

        // 6. Comptes clients de démonstration
        $this->db->table('compte_client')->insertBatch([
            ['numero' => '0331234567', 'solde' => 50000.0],
            ['numero' => '0379876543', 'solde' => 20000.0],
        ]);
    }
}
