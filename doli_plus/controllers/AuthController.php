<?php
namespace controllers;

use services\AuthService;
use yasmf\HttpHelper;
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
        $status_id = (int)HttpHelper::getParam('status_id') ?: 2 ;
        $start_letter = htmlspecialchars(HttpHelper::getParam('start_letter').'%') ?: '%';
        $search_stmt = $this->authService->findUsersByUsernameAndStatus($start_letter, $status_id) ;
        $view = new View("views/doli_plus");
        $view->setVar('search_stmt',$search_stmt);
        return $view;
    }

    /**
     * Action pour afficher la page de connexion
     *
     * @return View la vue de connexion
     */
    public function login(): View
    {
        $view = new View("views/gestion_note_frais_list");
        return $view;
    }

    /**
     * Action pour traiter la soumission du formulaire de connexion
     *
     * @return Redirect redirige l'utilisateur après la connexion
     */
    public function doLogin(): Redirect
    {
        // Récupérer les données soumises
        $username = HttpHelper::getParam('username');
        $password = HttpHelper::getParam('password');

        // Vérification des identifiants
        $isAuthenticated = $this->authService->authenticate($username, $password);

        if ($isAuthenticated) {
            // Connexion réussie, redirection vers la page d'accueil
            return new Redirect ("/home/index");
        } else {
            // Connexion échouée, redirection vers la page de connexion avec un message d'erreur
            return new Redirect("/home/login?error=true");
        }
    }
}
?>