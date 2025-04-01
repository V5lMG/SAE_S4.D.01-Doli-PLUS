<?php
namespace controllers;

use yasmf\View;
use services\AuthService;

/**
 * @author ValMG
 * @author Xavier Taborda Rodrigo
 * @author Thierry Erwan
 * @author Cambon Mathias
 * Pas de copyright, ni de droit d'auteur
 */
class AccueilController
{

    private AuthService $authService;

    /**
     * Crée un nouveau contrôleur par défaut
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Action par défaut pour l'affichage de la page d'accueil.
     * Cette méthode vérifie l'authentification de l'utilisateur et récupère l'URL saisie.
     * Si l'URL existe, elle est passée à la vue.
     *
     * @return View
     */
    public function index(): View
    {
        AuthService::checkAuthentication();

        // Vérifier si l'URL est déjà enregistrée
        $url = $_SESSION["url_saisie"] ?? '';
        $view = new View("views/accueil");

        // Passer les variables à la vue via setVar
        if ($url && !$this->urlExiste($url)) {
            $view->setVar('url', $url);
        }
        $view->setVar('controller', $this);
        $view->setVar('page', 'accueil');   // info de la page pour charger l'aide

        return $view;
    }

    /**
     * Action pour ajouter une nouvelle URL dans le fichier `url.conf`.
     * Cette méthode permet à l'utilisateur d'ajouter une nouvelle URL dans le fichier `url.conf`.
     * Si l'URL est valide, elle est ajoutée (ou replacée en haut si elle existe déjà).
     * Après l'ajout, l'utilisateur est redirigé vers la page d'authentification.
     *
     * @return void
     */
    public function addUrl(): void
    {
        $newUrl = $_POST["new_url"] ?? $_SESSION["url_saisie"];
        AuthService::setUrlFichier($newUrl);
        header("Location: index.php?controller=Accueil&action=index");
    }

    /**
     * Vérifie si l'URL existe déjà dans le fichier `url.conf`.
     * Cette méthode vérifie si l'URL passée en paramètre existe dans la liste des URLs stockées.
     *
     * @param string $url L'URL à vérifier.
     *
     * @return bool Retourne `true` si l'URL existe, sinon `false`.
     */
    public function urlExiste(string $url): bool
    {
        // Récupère les URLs stockées et les nettoie
        $urls = $this->authService->getUrlFichier();
        $cleanUrls = array_map('trim', $urls);
        $cleanUrl = trim($url);

        // Comparaison des URLs
        if (in_array($cleanUrl, $cleanUrls)) {
            return true;
        }
        return false;
    }
}