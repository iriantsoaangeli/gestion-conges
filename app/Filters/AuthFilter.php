<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Si pas connecté, redirige vers login
        if (!$session->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter d\'abord.');
        }

        // Vérifie le rôle si spécifié
        if (!empty($arguments) && is_array($arguments)) {
            $userRole = $session->get('user_role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/')->with('error', 'Accès refusé. Vous n\'avez pas le rôle requis.');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
