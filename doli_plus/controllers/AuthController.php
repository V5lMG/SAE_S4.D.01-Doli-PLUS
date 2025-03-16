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

    /**
     * Action pour afficher la page de menu
     *
     * @return View la vue de menu
     */
    public function login(): View
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Vérifier l'identifiant via l'API Dolibarr
            if ($this->authService->authentification($username, $password)) {
                // Authentification réussie → Redirection vers accueil.php
                header("Location: index.php?controller=Accueil&action=index");
                exit();
            } else {
                // Authentification échouée → Retour à la page de connexion avec un message d'erreur
                $view = new View("views/auth_page");
                $view->setVar("error", "Identifiant ou mot de passe incorrect.");
                return $view;
            }
        }

        return new View("views/auth_page");
    }

    /**
     * Gère la déconnexion de l'utilisateur
     *
     * @return void Redirige toujours vers la page de connexion
     */
    public function logout(): void
    {
        $this->authService->deconnexion();

        // Redirige vers la page de connexion
        header("Location: index.php?controller=Home&action=index");
        exit();
    }
}