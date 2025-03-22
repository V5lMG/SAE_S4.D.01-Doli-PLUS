<?php
namespace controllers;

use yasmf\View;
use services\AuthService;

class AccueilController
{
    public function index(): View
    {
        AuthService::checkAuthentication();

        // Récupérer l'URL actuelle de l'utilisateur (si nécessaire, selon la logique d'application)
        $currentUrl = $_SERVER['REQUEST_URI']; // Par exemple, obtenir l'URL de la page actuelle

        // Vérifier si l'URL a déjà été utilisée
        $urls = AuthService::getUrlFichier();
        $urlExists = in_array($currentUrl, $urls); // Vérifie si l'URL est dans la liste

        // Passer l'information à la vue
        return new View("views/accueil", [
            'urlExists' => $urlExists,
            'currentUrl' => $currentUrl
        ]);
    }

    /**
     * Action pour ajouter une nouvelle URL dans le fichier `url.conf`.
     *
     * Cette méthode permet à l'utilisateur d'ajouter une nouvelle URL dans le fichier `url.conf`.
     * Si l'URL est valide, elle est ajoutée (ou replacée en haut si elle existe déjà).
     * Après l'ajout, l'utilisateur est redirigé vers la page d'authentification.
     *
     * @return void
     */
    public function addUrl(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["new_url"])) {
            $newUrl = trim($_POST["new_url"]);

            // Vérifie si l'URL est valide
            if (filter_var($newUrl, FILTER_VALIDATE_URL)) {
                AuthService::setUrlFichier($newUrl);
            }

            // Redirection pour éviter une soumission multiple
            header("Location: index.php?controller=Home&action=index");
            exit;
        }
    }
}
