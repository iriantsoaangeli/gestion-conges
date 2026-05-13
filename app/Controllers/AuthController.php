<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function logout()
    {
        // Simulation - plus tard ce sera une vraie déconnexion
        return redirect()->to('/');
    }
}
