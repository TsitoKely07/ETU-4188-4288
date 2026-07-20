<?php

namespace App\Controllers\Client;

use App\Controllers\Client\BaseClientControlleur;

class AuthController extends BaseClientControlleur
{
    public function login()
    {
        return view('client/login');
    }

    public function loginProcess()
    {
        $numero = trim($this->request->getPost('numero'));

        // 1. Validation du préfixe
        $prefixes = $this->db->table('prefixe')->get()->getResultArray();
        $valide = false;

        foreach ($prefixes as $p) {
            if (str_starts_with($numero, $p['code'])) {
                $valide = true;
                break;
            }
        }

        if (!$valide) {
            return redirect()->back()->with('error', 'Numéro invalide pour cet opérateur.');
        }

        // 2. Vérification / Création automatique du compte
        $builder = $this->db->table('compte_client');
        $client = $builder->where('numero', $numero)->get()->getRowArray();

        if (!$client) {
            $builder->insert(['numero' => $numero, 'solde' => 0.0]);
            $clientId = $this->db->insertID();
            $client = ['id' => $clientId, 'numero' => $numero, 'solde' => 0.0];
        }

        session()->set('client', $client);
        return redirect()->to('/client/dashboard');
    }

    public function logout()
    {
        session()->remove('client');
        return redirect()->to('/');
    }
}