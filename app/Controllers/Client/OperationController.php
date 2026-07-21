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
        $clientNumero = session()->get('client')['numero'];
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



        // Détection opérateur source et destination
        $operateurSource = $this->getOperateurFromNumero($clientNumero);
        $operateurDest = $this->getOperateurFromNumero($destNumero);
        $estInteroperateur = ($operateurSource !== null && $operateurDest !== null && $operateurSource !== $operateurDest);

        // Calcul des frais de transfert
        if ($estInteroperateur) {
            // Transfert inter-opérateur : utiliser le barème externe si disponible, sinon standard
            $fraisTransfert = $this->getFrais(3, $montant, $operateurDest);
            
            // Pas de frais de retrait pour les autres opérateurs
            $fraisRetrait = 0.0;
            $inclureFraisRetrait = false; // Force à false

            // Calcul commission inter-opérateur (pourcentage du montant)
            $pourcentageCommission = $this->getCommissionInteroperateur($operateurSource, $operateurDest);
            $commissionMontant = $montant * ($pourcentageCommission / 100);
        } else {
            // Transfert interne (même opérateur ou générique)
            $fraisTransfert = $this->getFrais(3, $montant);
            $fraisRetrait = $inclureFraisRetrait ? $this->getFrais(2, $montant) : 0.0;
            $commissionMontant = 0.0;
            $operateurDest = null; // Pas d'opérateur destination spécifique
        }



        $totalFrais = $fraisTransfert + $fraisRetrait + $commissionMontant;
        $totalAEnleverExpediteur = $montant + $totalFrais;
        $montantCrediteDestinataire = $montant + $fraisRetrait;

        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        if ($client['solde'] < $totalAEnleverExpediteur) {
            $msg = "Solde insuffisant (Montant: {$montant} Ar + Frais: {$totalFrais} Ar";
            if ($estInteroperateur) {
                $msg .= " dont commission {$commissionMontant} Ar";
            }
            $msg .= ").";
            return redirect()->back()->with('error', $msg);
        }

        // Transaction : Débit expéditeur & Crédit destinataire
        $this->db->query("UPDATE compte_client SET solde = solde - ? WHERE id = ?", [$totalAEnleverExpediteur, $clientId]);
        $this->db->query("UPDATE compte_client SET solde = solde + ? WHERE id = ?", [$montantCrediteDestinataire, $destinataire['id']]);

        // Historique des frais payés
        $fraisTotauxEnregistres = $fraisTransfert + $fraisRetrait + $commissionMontant;

        $this->db->table('historique_operation')->insert([
            'id_compte_source'      => $clientId,
            'id_compte_dest'        => $destinataire['id'],
            'id_type_operation'     => 3,
            'montant'               => $montant,
            'frais'                 => $fraisTotauxEnregistres,
            'id_operateur_destination' => $operateurDest,
            'frais_retrait_inclus'  => $inclureFraisRetrait ? 1 : 0
        ]);

        $msg = 'Transfert réussi !';
        if ($estInteroperateur) {
            $msg .= " (Transfert inter-opérateur, commission: {$pourcentageCommission}%)";
        }
        return redirect()->to('/client/dashboard')->with('success', $msg);
    }

    public function transfertMultiple()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $clientId = session()->get('client')['id'];
        $clientNumero = session()->get('client')['numero'];
        $operateurSource = $this->getOperateurFromNumero($clientNumero);

        // 1. Récupération des deux numéros destinataires
        $num1 = trim((string) $this->request->getPost('numero_dest_1'));
        $num2 = trim((string) $this->request->getPost('numero_dest_2'));

        // Si la vue envoie un champ 'numeros' sous forme de liste (fallback)
        if (empty($num1) && empty($num2)) {
            $rawNumeros = $this->request->getPost('numeros');
            $listeNumeros = array_unique(array_filter(array_map('trim', preg_split('/[\s,]+/', (string)$rawNumeros))));
        } else {
            $listeNumeros = array_unique(array_filter([$num1, $num2]));
        }

        // 2. Récupération du montant (compatible 'montant' et 'montant_total')
        $montantInput = $this->request->getPost('montant') ?? $this->request->getPost('montant_total');
        $montantParPersonne = (float) $montantInput;
        $inclureFraisRetrait = (bool) $this->request->getPost('inclure_frais_retrait');

        if ($montantParPersonne <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }

        if (count($listeNumeros) < 2) {
            return redirect()->back()->with('error', 'Veuillez renseigner deux numéros destinataires valides et différents.');
        }

        // 3. Contrôle de l'existence des destinataires
        $destinataires = [];
        $estInteroperateurGlobal = false;

        foreach ($listeNumeros as $num) {
            $dest = $this->db->table('compte_client')->where('numero', $num)->get()->getRowArray();
            if (!$dest) {
                return redirect()->back()->with('error', "Le numéro {$num} n'existe pas dans le système.");
            }
            if ($dest['id'] == $clientId) {
                return redirect()->back()->with('error', "Vous ne pouvez pas vous inclure dans la liste des destinataires.");
            }
            $destinataires[] = $dest;

            $operateurDestCheck = $this->getOperateurFromNumero($num);
            if ($operateurSource !== null && $operateurDestCheck !== null && $operateurSource !== $operateurDestCheck) {
                $estInteroperateurGlobal = true;
            }
        }

        // 4. Pré-calcul des coûts et commissions pour chaque destinataire
        $coutTotalGlobal = 0.0;
        $detailsTransactions = [];

        foreach ($destinataires as $dest) {
            $operateurDest = $this->getOperateurFromNumero($dest['numero']);
            $estInterop = ($operateurSource !== null && $operateurDest !== null && $operateurSource !== $operateurDest);

            if ($estInterop) {
                // Inter-opérateur : commission (1% ou configurée) + pas de frais de retrait
                $fraisTransfert = $this->getFrais(3, $montantParPersonne, $operateurDest);
                $fraisRetrait = 0.0;

                $pourcentageComm = $this->getCommissionInteroperateur($operateurSource, $operateurDest);
                $commission = ($pourcentageComm > 0) ? ($montantParPersonne * ($pourcentageComm / 100)) : ($montantParPersonne * 0.01);
            } else {
                // Même opérateur : Frais de transfert standards + Frais de retrait optionnels
                $fraisTransfert = $this->getFrais(3, $montantParPersonne);
                $fraisRetrait = $inclureFraisRetrait ? $this->getFrais(2, $montantParPersonne) : 0.0;
                $commission = 0.0;
            }

            $fraisTotauxUnitaires = $fraisTransfert + $fraisRetrait + $commission;
            $coutUnitaireExpediteur = $montantParPersonne + $fraisTotauxUnitaires;

            $coutTotalGlobal += $coutUnitaireExpediteur;

            $detailsTransactions[] = [
                'dest'                  => $dest,
                'operateur_dest'        => $operateurDest,
                'est_interop'           => $estInterop,
                'frais_totaux'          => $fraisTotauxUnitaires,
                'frais_retrait'         => $fraisRetrait,
                'cout_unitaire'         => $coutUnitaireExpediteur,
            ];
        }

        // Vérification du solde global
        $client = $this->db->table('compte_client')->where('id', $clientId)->get()->getRowArray();
        if ($client['solde'] < $coutTotalGlobal) {
            return redirect()->back()->with('error', "Solde insuffisant pour exécuter cet envoi multiple. Montant total requis (avec frais et commissions) : " . number_format($coutTotalGlobal, 2, ',', ' ') . " Ar.");
        }

        // 5. Exécution des débits, crédits et enregistrement de l'historique
        foreach ($detailsTransactions as $item) {
            $dest = $item['dest'];
            $montantCredite = $montantParPersonne + $item['frais_retrait'];

            // Débit de l'expéditeur
            $this->db->query("UPDATE compte_client SET solde = solde - ? WHERE id = ?", [$item['cout_unitaire'], $clientId]);

            // Crédit du destinataire
            $this->db->query("UPDATE compte_client SET solde = solde + ? WHERE id = ?", [$montantCredite, $dest['id']]);

            // Insertion dans la table historique_operation
            $this->db->table('historique_operation')->insert([
                'id_compte_source'         => $clientId,
                'id_compte_dest'           => $dest['id'],
                'id_type_operation'        => 3,
                'montant'                  => $montantParPersonne,
                'frais'                    => $item['frais_totaux'],
                'id_operateur_destination' => $item['est_interop'] ? $item['operateur_dest'] : null,
                'frais_retrait_inclus'     => ($item['frais_retrait'] > 0) ? 1 : 0
            ]);
        }

        $montantAffiche = number_format($montantParPersonne, 2, ',', ' ');
        $msg = "Transfert multiple de {$montantAffiche} Ar réussi vers les 2 destinataires !";
        if ($estInteroperateurGlobal) {
            $msg .= " (Commission inter-opérateur appliquée).";
        }

        return redirect()->to('/client/dashboard')->with('success', $msg);
    }

    public function Promotion(){
        if ($redirect = $this->checkAuth()) return $redirect;

        
    }
}