<?php


namespace App\Controllers\operator;

use App\Controllers\BaseController;

use App\Models\PrefixeModel;
use CodeIgniter\Controller;

class OperateurController extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    protected function checkAuth()
    {
        if (!session()->has('operator')) {
            return redirect()->to('/operator/login');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        return redirect()->to('/operator/gains');
    }

    public function gains()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'gains';

        $data['gains_propres'] = $this->db->query("
            SELECT t.nom as type, SUM(frais) as total_frais 
            FROM historique_operation tx
            JOIN type_operation t ON tx.id_type_operation = t.id
            WHERE t.nom IN ('retrait', 'transfert')
            AND tx.id_operateur_destination IS NULL
            GROUP BY t.nom
        ")->getResultArray();


        $data['gains_interoperateur'] = $this->db->query("
            SELECT o.nom AS operateur_dest, SUM(tx.frais) as total_commission
            FROM historique_operation tx
            JOIN operateur o ON tx.id_operateur_destination = o.id
            WHERE tx.id_type_operation = 3
            GROUP BY o.nom
        ")->getResultArray();

        return view('operator/gains', $data);
    }

    public function clients()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'clients';
        $data['clients'] = $this->db->query("
            SELECT numero AS numero_telephone, solde 
            FROM compte_client 
            ORDER BY solde DESC
        ")->getResultArray();

        return view('operator/clients', $data);
    }

    public function prefixes()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'prefixes';
        $data['prefixes'] = $this->db->query("
            SELECT p.id, p.code AS prefixe, p.id_operateur, COALESCE(o.nom, 'Générique') AS operateur_nom
            FROM prefixe p
            LEFT JOIN operateur o ON p.id_operateur = o.id
            ORDER BY o.nom, p.code
        ")->getResultArray();

        $data['operateurs'] = $this->db->query("SELECT id, nom FROM operateur ORDER BY nom")->getResultArray();

        return view('operator/prefixes', $data);
    }

    public function baremes()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'baremes';
        $data['baremes'] = $this->db->query("
            SELECT b.*, t.nom as type_nom, COALESCE(o.nom, 'Standard') as operateur_nom
            FROM bareme_frais b
            JOIN type_operation t ON b.id_type_operation = t.id
            LEFT JOIN operateur o ON b.id_operateur = o.id
            ORDER BY b.id_type_operation, b.montant_min
        ")->getResultArray();

        $data['operateurs'] = $this->db->query("SELECT id, nom FROM operateur ORDER BY nom")->getResultArray();

        return view('operator/baremes', $data);
    }


    public function addPrefix()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $prefix = $this->request->getPost('prefixe');
        $idOperateur = $this->request->getPost('id_operateur');
        if (!empty($prefix)) {
            $operateurValue = !empty($idOperateur) ? (int)$idOperateur : null;
            $this->db->query("INSERT OR IGNORE INTO prefixe (code, id_operateur) VALUES (?, ?)", [$prefix, $operateurValue]);
        }
        return redirect()->to('/operator/prefixes');
    }


    public function saveBareme()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $id_type = $this->request->getPost('id_type_operation');
        $min = $this->request->getPost('montant_min');
        $max = $this->request->getPost('montant_max');
        $frais = $this->request->getPost('frais');
        $id_operateur = $this->request->getPost('id_operateur');
        $type_frais = $this->request->getPost('type_frais');

        $this->db->query("
            INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais, id_operateur, type_frais) 
            VALUES (?, ?, ?, ?, ?, ?)
        ", [$id_type, $min, $max, $frais, !empty($id_operateur) ? (int)$id_operateur : null, !empty($type_frais) ? $type_frais : 'standard']);

        return redirect()->to('/operator/baremes');
    }

    public function commissions()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'commissions';
        $data['commissions'] = $this->db->query("
            SELECT c.*, src.nom AS operateur_source_nom, dst.nom AS operateur_dest_nom
            FROM commission_interoperateur c
            JOIN operateur src ON c.id_operateur_source = src.id
            JOIN operateur dst ON c.id_operateur_destination = dst.id
            ORDER BY src.nom, dst.nom
        ")->getResultArray();

        $data['operateurs'] = $this->db->query("SELECT id, nom FROM operateur ORDER BY nom")->getResultArray();

        return view('operator/commissions', $data);
    }

    public function saveCommission()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $id_operateur_source = (int)$this->request->getPost('id_operateur_source');
        $id_operateur_destination = (int)$this->request->getPost('id_operateur_destination');
        $pourcentage = (float)$this->request->getPost('pourcentage_commission');

        if ($id_operateur_source > 0 && $id_operateur_destination > 0 && $pourcentage > 0) {
            $this->db->query("
                INSERT OR REPLACE INTO commission_interoperateur (id_operateur_source, id_operateur_destination, pourcentage_commission)
                VALUES (?, ?, ?)
            ", [$id_operateur_source, $id_operateur_destination, $pourcentage]);
        }

        return redirect()->to('/operator/commissions');
    }

    public function decompte()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'decompte';

        // Montants à envoyer à chaque opérateur (basé sur les commissions)
        $data['decomptes'] = $this->db->query("
            SELECT 
                o.id AS operateur_id,
                o.nom AS operateur_nom,
                COALESCE(SUM(tx.frais), 0) AS montant_commission,
                COALESCE(d.montant_deja_envoye, 0) AS deja_envoye
            FROM operateur o
            LEFT JOIN historique_operation tx ON tx.id_operateur_destination = o.id
            LEFT JOIN decompte_operateur d ON d.id_operateur = o.id 
                AND d.mois_annee = strftime('%Y-%m', 'now')
            GROUP BY o.id
            ORDER BY o.nom
        ")->getResultArray();

        return view('operator/decompte', $data);
    }

    public function marquerEnvoye()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $id_operateur = (int)$this->request->getPost('id_operateur');
        $montant = (float)$this->request->getPost('montant_envoye');
        $mois_annee = date('Y-m');

        if ($id_operateur > 0 && $montant > 0) {
            $existing = $this->db->query("
                SELECT id FROM decompte_operateur 
                WHERE id_operateur = ? AND mois_annee = ?
            ", [$id_operateur, $mois_annee])->getRowArray();

            if ($existing) {
                $this->db->query("
                    UPDATE decompte_operateur 
                    SET montant_deja_envoye = montant_deja_envoye + ?, statut = CASE WHEN montant_deja_envoye + ? >= montant_total_a_envoyer THEN 'paye' ELSE 'en_attente' END
                    WHERE id = ?
                ", [$montant, $montant, $existing['id']]);
            } else {
                $this->db->query("
                    INSERT INTO decompte_operateur (id_operateur, mois_annee, montant_total_a_envoyer, montant_deja_envoye, statut)
                    VALUES (?, ?, 0, ?, 'partiel')
                ", [$id_operateur, $mois_annee, $montant]);
            }
        }

        return redirect()->to('/operator/decompte');
    }
}
