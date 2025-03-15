<?php
namespace controllers;

use services\AuthService;
use yasmf\View;

class AuthController
{
    private AuthService $authService;

    /**
     * Create a new default controller
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Default action
     *
     * @return View the default view displaying all users
     */
    public function index(): View {
        return new View("views/auth_page");
    }

//    /**
//     * Action pour afficher la page de connexion
//     *
//     * @return View la vue de connexion
//     */
//    public function login(): View
//    {
//        return new View("views/gestion_note_frais_list");
//    }
}