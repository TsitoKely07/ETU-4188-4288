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

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }

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
        $inclureFraisRetrait = (bool) $this->request->getPost('inclure_frais_retrait');

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }

        $destinataire = $this->db->table('compte_client')->where('numero', $destNumero)->get()->getRowArray();
        
        if (!$destinataire) {
            return redirect()->back()->with('error', 'Numéro destinataire introuvable.');
        }

        if ($destinataire['id'] == $clientId) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas faire un transfert vers vous-même.');
        }

        // Calcul des frais
        $fraisTransfert = $this->getFrais(3, $montant); // Type 3 = Transfert
        $fraisRetrait = $inclureFraisRetrait ? $this->getFrais(2, $montant) : 0.0; // Type 2 = Retrait

        $totalAEnleverExpediteur = $montant + $fraisTransfert + $fraisRetrait;
        $montantCrediteDestinataire = $montant + $fraisRetrait;

        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        if ($client['solde'] < $totalAEnleverExpediteur) {
            $msg = "Solde insuffisant (Montant: {$montant} Ar + Frais transfert: {$fraisTransfert} Ar";
            if ($inclureFraisRetrait) {
                $msg .= " + Frais retrait offert: {$fraisRetrait} Ar";
            }
            $msg .= ").";
            return redirect()->back()->with('error', $msg);
        }

        // Transaction : Débit expéditeur & Crédit destinataire
        $this->db->query("UPDATE compte_client SET solde = solde - ? WHERE id = ?", [$totalAEnleverExpediteur, $clientId]);
        $this->db->query("UPDATE compte_client SET solde = solde + ? WHERE id = ?", [$montantCrediteDestinataire, $destinataire['id']]);

        // Historique des frais payés
        $fraisTotauxEnregistres = $fraisTransfert + $fraisRetrait;

        $this->db->table('historique_operation')->insert([
            'id_compte_source' => $clientId,
            'id_compte_dest'   => $destinataire['id'],
            'id_type_operation'=> 3,
            'montant'          => $montant,
            'frais'            => $fraisTotauxEnregistres
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Transfert réussi !');
    }

    public function transfertMultiple()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientId = session()->get('client')['id'];
        $rawNumeros = $this->request->getPost('numeros');
        $montantTotal = (float) $this->request->getPost('montant_total');
        $inclureFraisRetrait = (bool) $this->request->getPost('inclure_frais_retrait');

        if ($montantTotal <= 0) {
            return redirect()->back()->with('error', 'Montant total invalide.');
        }

        // Séparation et nettoyage de la liste des numéros
        $listeNumeros = array_unique(array_filter(array_map('trim', preg_split('/[\s,]+/', $rawNumeros))));

        if (empty($listeNumeros)) {
            return redirect()->back()->with('error', 'Veuillez renseigner au moins un numéro destinataire.');
        }

        $nbDestinataires = count($listeNumeros);
        $montantParPersonne = $montantTotal / $nbDestinataires;

        // 1. Contrôle des destinataires
        $destinataires = [];
        foreach ($listeNumeros as $num) {
            $dest = $this->db->table('compte_client')->where('numero', $num)->get()->getRowArray();
            if (!$dest) {
                return redirect()->back()->with('error', "Le numéro {$num} n'existe pas dans le système.");
            }
            if ($dest['id'] == $clientId) {
                return redirect()->back()->with('error', "Vous ne pouvez pas vous inclure dans la liste des destinataires.");
            }
            $destinataires[] = $dest;
        }

        // 2. Calcul des coûts par destinataire
        $fraisTransfertUnitaire = $this->getFrais(3, $montantParPersonne);
        $fraisRetraitUnitaire   = $inclureFraisRetrait ? $this->getFrais(2, $montantParPersonne) : 0.0;

        $coutUnitaireExpediteur = $montantParPersonne + $fraisTransfertUnitaire + $fraisRetraitUnitaire;
        $coutTotalGlobal        = $coutUnitaireExpediteur * $nbDestinataires;

        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        if ($client['solde'] < $coutTotalGlobal) {
            return redirect()->back()->with('error', "Solde insuffisant pour exécuter cet envoi multiple. Montant requis : " . number_format($coutTotalGlobal, 2, ',', ' ') . " Ar.");
        }

        // 3. Exécution des transferts
        foreach ($destinataires as $dest) {
            $montantCredite = $montantParPersonne + $fraisRetraitUnitaire;
            $fraisTotauxUnitaires = $fraisTransfertUnitaire + $fraisRetraitUnitaire;

            $this->db->query("UPDATE compte_client SET solde = solde - ? WHERE id = ?", [$coutUnitaireExpediteur, $clientId]);
            $this->db->query("UPDATE compte_client SET solde = solde + ? WHERE id = ?", [$montantCredite, $dest['id']]);

            $this->db->table('historique_operation')->insert([
                'id_compte_source' => $clientId,
                'id_compte_dest'   => $dest['id'],
                'id_type_operation'=> 3,
                'montant'          => $montantParPersonne,
                'frais'            => $fraisTotauxUnitaires
            ]);
        }

        return redirect()->to('/client/dashboard')->with('success', "Envoi multiple de " . number_format($montantTotal, 2, ',', ' ') . " Ar réussi vers {$nbDestinataires} destinataires !");
    }
}