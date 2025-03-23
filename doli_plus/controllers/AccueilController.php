<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use services\AuthService;

class AccueilController
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
     * TODO
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
            $view->setVar('controller', $this);
        } else {
            $view->setVar('controller', $this);
        }

        return $view;
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
        if (isset($_POST["new_url"])) {
            $newUrl = $_POST["new_url"];
            AuthService::setUrlFichier($newUrl);
            exit;
        }
    }

    public function urlEnHaut(): void
    {
        $url = $_SESSION["url_saisie"] ?? '';
        AuthService::setUrlFichier($url);

    }


    /**
     * @param string $url
     * @return bool
     */
    public function urlExiste(string $url): bool
    {
        // Récupère les URLs stockées et les nettoie
        $urls = $this->authService->getUrlFichier();
        $cleanUrls = array_map('trim', $urls); // Enlève les espaces et retours à la ligne des URLs stockées

        // Nettoie l'URL passée en paramètre
        $cleanUrl = strtolower(trim($url)); // Enlève les espaces et met en minuscule

        var_dump($cleanUrls); // Affiche les URLs stockées après nettoyage
        var_dump($cleanUrl); // Affiche l'URL à tester

        // Comparaison explicite des URLs
        if (in_array($cleanUrl, $cleanUrls)) {
            var_dump("URL trouvée!"); // Vérifie que cette ligne est atteinte
            return true;
        } else {
            var_dump("URL NON trouvée!"); // Vérifie que cette ligne est atteinte
            return false;
        }
    }

}
