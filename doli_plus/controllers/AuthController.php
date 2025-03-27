<?php
namespace controllers;

use services\AuthService;
use yasmf\View;

class AuthController
{
    private AuthService $authService;

    /**
     * Crée un nouveau contrôleur par défaut pour l'authentification.
     *
     * @param AuthService $authService Le service d'authentification à utiliser pour les opérations.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Action par défaut pour afficher la page d'authentification avec la liste des URLs.
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
     * Action pour afficher la page de menu et effectuer l'authentification.
     * Cette méthode vérifie si les informations d'identification sont valides via l'API Dolibarr.
     * En cas de succès, elle redirige l'utilisateur vers la page d'accueil, sinon elle affiche un message d'erreur.
     *
     * @return View La vue de menu, ou une redirection en cas d'authentification réussie ou échouée.
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
                $this->authService->droits();
                header("Location: index.php?controller=Accueil&action=index");
            } else {
                // Authentification échouée → Retour à la page de connexion avec un message d'erreur
                $_SESSION['error_message'] = "Identifiant, mot de passe ou URL incorrect";
                header("Location: index.php");
            }
            exit();
        }

        return new View("index"); // défaut si aucune soumission n'est faites
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     * Cette méthode supprime les informations de session de l'utilisateur et redirige vers la page de connexion.
     *
     * @return void Redirige toujours vers la page de connexion après la déconnexion.
     */
    public function logout(): void
    {
        $this->authService->deconnexion();

        // Redirige vers la page de connexion
        header("Location: index.php?controller=Home&action=index");
        exit();
    }
}