<?php

namespace App\Controllers\Client;

class OperationController extends BaseClientController
{
    public function depot()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientId = session()->get('client')['id'];
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }


        $this->db->query("UPDATE compte_client SET solde = solde + ? WHERE id = ?", [$montant, $clientId]);

        // Historique (Type 1 = Dépôt)
        $this->db->table('historique_operation')->insert([
            'id_compte_source' => $clientId,
            'id_type_operation' => 1,
            'montant' => $montant,
            'frais' => 0.0
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Dépôt effectué avec succès.');
    }

    public function retrait()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientId = session()->get('client')['id'];
        $montant = (float) $this->request->getPost('montant');

        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        $frais = $this->getFrais(2, $montant); // Type 2 = Retrait
        $totalAEnlever = $montant + $frais;

        if ($client['solde'] < $totalAEnlever) {
            return redirect()->back()->with('error', "Solde insuffisant (Montant + Frais de {$frais} Ar).");
        }

        $this->db->query("UPDATE compte_client SET solde = solde - ? WHERE id = ?", [$totalAEnlever, $clientId]);

        $this->db->table('historique_operation')->insert([
            'id_compte_source' => $clientId,
            'id_type_operation' => 2,
            'montant' => $montant,
            'frais' => $frais
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Retrait effectué avec succès.');
    }

    public function transfert()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientId = session()->get('client')['id'];
        $destNumero = trim($this->request->getPost('numero_dest'));
        $montant = (float) $this->request->getPost('montant');

        $destinataire = $this->db->table('compte_client')->where('numero', $destNumero)->get()->getRowArray();
        
        if (!$destinataire) {
            return redirect()->back()->with('error', 'Numéro destinataire introuvable.');
        }

        if ($destinataire['id'] == $clientId) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas faire un transfert vers vous-même.');
        }

        $frais = $this->getFrais(3, $montant); // Type 3 = Transfert
        $totalAEnlever = $montant + $frais;

        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        if ($client['solde'] < $totalAEnlever) {
            return redirect()->back()->with('error', "Solde insuffisant (Montant + Frais de {$frais} Ar).");
        }

        // Transaction
        $this->db->query("UPDATE compte_client SET solde = solde - ? WHERE id = ?", [$totalAEnlever, $clientId]);
        $this->db->query("UPDATE compte_client SET solde = solde + ? WHERE id = ?", [$montant, $destinataire['id']]);

        $this->db->table('historique_operation')->insert([
            'id_compte_source' => $clientId,
            'id_compte_dest' => $destinataire['id'],
            'id_type_operation' => 3,
            'montant' => $montant,
            'frais' => $frais
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Transfert réussi !');
    }
}