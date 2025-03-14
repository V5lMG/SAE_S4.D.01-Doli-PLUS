<?php
namespace controllers;

use services\AuthService;
use yasmf\HttpHelper;
use yasmf\View;

class DisconnectController
{

    /**
     * Action par défaut pour se déconnecter
     *
     * @return View La vue de la page de connexion
     */
    public function index(): View
    {
        $view = new View("doli_plus/views/doli_plus");

        // Déconnexion de l'utilisateur
        session_start();
        session_destroy();

        return $view;
    }
}
