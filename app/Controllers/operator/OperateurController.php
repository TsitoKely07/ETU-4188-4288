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
        $data['gains'] = $this->db->query("
            SELECT t.nom as type, SUM(frais) as total_frais 
            FROM historique_operation tx
            JOIN type_operation t ON tx.id_type_operation = t.id
            WHERE t.nom IN ('retrait', 'transfert')
            GROUP BY t.nom
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
        $data['prefixes'] = $this->db->query("SELECT id, code AS prefixe FROM prefixe")->getResultArray();

        return view('operator/prefixes', $data);
    }

    public function baremes()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data['current_page'] = 'baremes';
        $data['baremes'] = $this->db->query("
            SELECT b.*, t.nom as type_nom 
            FROM bareme_frais b
            JOIN type_operation t ON b.id_type_operation = t.id
            ORDER BY b.id_type_operation, b.montant_min
        ")->getResultArray();

        return view('operator/baremes', $data);
    }


    public function addPrefix()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $prefix = $this->request->getPost('prefixe');
        if (!empty($prefix)) {
            $this->db->query("INSERT OR IGNORE INTO prefixe (code) VALUES (?)", [$prefix]);
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

        $this->db->query("
            INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) 
            VALUES (?, ?, ?, ?)
        ", [$id_type, $min, $max, $frais]);

        return redirect()->to('/operator/baremes');
    }
}