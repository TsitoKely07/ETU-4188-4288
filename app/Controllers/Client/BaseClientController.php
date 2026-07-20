<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;

class BaseClientControlleur extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Récupérer le barème de frais selon l'opération et le montant
    protected function getFrais(int $typeOpId, float $montant): float
    {
        $bareme = $this->db->table('bareme_frais')
            ->where('id_type_operation', $typeOpId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->get()->getRowArray();

        return $bareme ? (float)$bareme['frais'] : 0.0;
    }

    // Vérifier si la session client est active
    protected function checkAuth()
    {
        if (!session()->has('client')) {
            return redirect()->to('/');
        }
        return null;
    }
}