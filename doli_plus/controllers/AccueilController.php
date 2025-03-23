<?php
namespace controllers;

use yasmf\View;
use services\AuthService;

class AccueilController
{

    /**
     * TODO
     * @return View
     */
    public function index(): View
    {
        AuthService::checkAuthentication();
        return new View("views/accueil");
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
        if (!empty($_POST["new_url"])) {
            $newUrl = $_POST["new_url"];
            AuthService::setUrlFichier($newUrl);
            exit;
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    public function urlExiste(string $url): bool
    {
        $urls = $this->authService->getUrlFichier(); // Récupère les URLs stockées
        return in_array($url, array_map('trim', $urls)); // Vérifie si l'URL est déjà enregistré
    }
}
