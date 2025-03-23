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
     * Action par défaut pour afficher la page d'authentification avec la liste des URLs.
     *
     * Cette méthode récupère les URLs enregistrées dans le fichier `url.conf` via la méthode
     * `getUrlFichier()` du service d'authentification, puis les passe à la vue pour affichage.
     *
     * @return View La vue contenant le formulaire de connexion et la liste des URLs.
     */
    public function index(): View {

        $urls = $this->authService->getUrlFichier();

        $view = new View("views/auth_page");
        $view->setVar('urls', $urls);

        return $view;
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
            $url      = $_POST['url'];

            // Vérifier l'identifiant via l'API Dolibarr
            if ($this->authService->authentification($username, $password, $url)) {
                // Authentification réussie → Redirection vers accueil.php
                $this->authService->urlSession($url);
                header("Location: index.php?controller=Accueil&action=index");
                exit();
            } else {
                // Authentification échouée → Retour à la page de connexion avec un message d'erreur
                $_SESSION['error_message'] = "Identifiant, mot de passe ou URL incorrect";
                header("Location: index.php");
                exit();
            }
        }

        return new View("index"); // défaut si aucune soumission n'est faites
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