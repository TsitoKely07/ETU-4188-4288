<?php

namespace App\Controllers\operator;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        return view('operator/login');
    }

    public function loginProcess()
    {
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        $validUsername = env('operator.username') ?: 'operateur';
        $validPassword = env('operator.password') ?: 'operateur123';

        if ($username !== $validUsername || $password !== $validPassword) {
            return redirect()->back()->with('error', 'Identifiants invalides.');
        }

        session()->set('operator', ['username' => $username]);
        return redirect()->to('/operator');
    }

    public function logout()
    {
        session()->remove('operator');
        return redirect()->to('/operator/login');
    }
}
