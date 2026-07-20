<?php

namespace App\Controllers\Client;

class DashboardController extends BaseClientController
{
    public function index()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientId = session()->get('client')['id'];

        // Rafraîchir le solde en session
        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        session()->set('client', $client);

        // Récupération rapide des 5 dernières opérations
        $historiques = $this->db->query("
            SELECT h.*, t.nom as type_nom 
            FROM historique_operation h
            JOIN type_operation t ON h.id_type_operation = t.id
            WHERE h.id_compte_source = ? OR h.id_compte_dest = ?
            ORDER BY h.date_operation DESC LIMIT 5
        ", [$clientId, $clientId])->getResultArray();

        return view('client/dashboard', [
            'client' => $client,
            'historiques' => $historiques
        ]);
    }
}