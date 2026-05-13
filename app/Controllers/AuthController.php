<?php

namespace App\Controllers;

use App\Models\Employe;

class AuthController extends BaseController
{
    protected $employeModel;

    public function __construct()
    {
        $this->employeModel = new Employe();
    }

    public function login()
    {
        // Si déjà connecté, redirige vers son espace
        if (session()->has('user_id')) {
            $role = session()->get('user_role');
            return redirect()->to('/' . $role);
        }

        if ($this->request->is('post')) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            try {
                // Cherche l'employé par email
                $employe = $this->employeModel
                    ->select('employes.*, roles.nom as role_name, departements.nom as departement_name')
                    ->join('roles', 'employes.role_id = roles.id', 'left')
                    ->join('departements', 'employes.departement_id = departements.id', 'left')
                    ->where('employes.email', $email)
                    ->where('employes.actif', true)
                    ->first();

                // Vérifie le mot de passe
                if ($employe && password_verify($password, $employe['password'])) {
                    // Définit la session
                    session()->set([
                        'user_id' => $employe['id'],
                        'user_email' => $employe['email'],
                        'user_prenom' => $employe['prenom'],
                        'user_nom' => $employe['nom'],
                        'user_role' => $employe['role_name'],
                        'user_departement' => $employe['departement_name'],
                    ]);

                    return redirect()->to('/' . $employe['role_name'])->with('success', 'Bienvenue ' . $employe['prenom'] . ' !');
                }
            } catch (\Throwable $e) {
                log_message('error', 'Erreur login: {message}', ['message' => $e->getMessage()]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Connexion impossible pour le moment. Vérifie le journal applicatif.');
            }

            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Vous avez été déconnecté.');
    }
}

