<?php
namespace controllers;

use services\AuthService;
use services\FournisseurService;
use yasmf\HttpHelper;
use yasmf\View;

class FournisseurController
{
    private FournisseurService $fournisseurService;

    /**
     * Crée un nouveau contrôleur de gestion des fournisseurs.
     *
     * @param FournisseurService $fournisseurService Le service de gestion des fournisseurs.
     */
    public function __construct(FournisseurService $fournisseurService)
    {
        $this->fournisseurService = $fournisseurService;
    }

    /**
     * Affiche la page de liste des fournisseurs.
     * Vérifie que l'utilisateur est authentifié avant d'afficher la vue.
     *
     * @return View La vue de la page de liste des fournisseurs.
     */
    public function index(): View
    {
        AuthService::checkAuthentication();
        $view = new View("views/liste_fournisseur");
        $view->setVar('page', 'fournisseur');   // info de la page pour charger l'aide
        return $view;
    }

    /**
     * Affiche la page de palmarès des fournisseurs.
     * Vérifie que l'utilisateur est authentifié avant d'afficher la vue.
     *
     * @return View La vue de la page des palmarès.
     */
    public function indexPalmares(): View
    {
        AuthService::checkAuthentication();

        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $date_debut  = HttpHelper::getParam('date_debut');
        $date_fin    = HttpHelper::getParam('date_fin');

        $listPalmares = $this->fournisseurService->recupererListeCompletePalmares($date_debut, $date_fin);

        $top = (int)HttpHelper::getParam('top') === 0 ? 30 : (int)HttpHelper::getParam('top');

        // Passer les données à la vue
        $view = new View("views/palmares_fournisseur");
        $view->setVar('listePalmares', $listPalmares);
        $view->setVar('top', $top);
        $view->setVar('date_debut', $date_debut);
        $view->setVar('date_fin', $date_fin);
        $view->setVar('page', 'palmares');   // info de la page pour charger l'aide
        return $view;
    }

    /**
     * Affiche la liste des fournisseurs.
     * Applique les filtres récupérés via les paramètres HTTP.
     *
     * @return View La vue filtrée des notes de fournisseurs.
     */
    public function indexListe(): View
    {
        AuthService::checkAuthentication();

        // Démarre la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupérer la liste des fournisseurs
        $listeFournisseurNonTrie = $this->fournisseurService->recupererListeComplete();

        // Récupération des paramètres de filtre
        
        // Récupérer les paramètres depuis la session
        $nom        = isset($_SESSION['nom']) ? $_SESSION['nom'] : HttpHelper::getParam('nom');
        $numTel     = isset($_SESSION['numTel']) ? $_SESSION['numTel'] : HttpHelper::getParam('numTel');
        $adresse    = isset($_SESSION['adresse']) ? $_SESSION['adresse'] : HttpHelper::getParam('adresse');
        $codePostal = isset($_SESSION['codePostal']) ? $_SESSION['codePostal'] : HttpHelper::getParam('codePostal');

        // Appliquer les filtres et récupérer les données
        $filteredData = $this->fournisseurService->filtrerValeurs(
            $listeFournisseurNonTrie ,
            $nom,
            $numTel,
            $adresse,
            $codePostal,
        );

        $listeFournisseur = $filteredData; // à voir le "notes"

        // Passer les données à la vue
        $view = new View("views/liste_fournisseur");
        $view->setVar('listeFournisseur', $listeFournisseur);
        $view->setVar('page', 'fournisseur');   // info de la page pour charger l'aide
        return $view;
    }

    /**
     * Affiche la liste des factures d'un fournisseur donné.
     *
     * @return View La vue contenant la liste des factures du fournisseur.
     */
    public function indexFactures(): View
    {
        AuthService::checkAuthentication();

        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier que les paramètres existent dans la requête GET
        $nomFournisseur = isset($_GET['nomFournisseur']) ? htmlspecialchars($_GET['nomFournisseur']) : "Inconnu";
        $refFournisseur = isset($_GET['refFournisseur']) ? htmlspecialchars($_GET['refFournisseur']) : "Inconnu";

        // Récupérer les factures
        $factures = $this->fournisseurService->factureFournisseur($refFournisseur);

        // Passer les variables à la vue
        $view = new View("views/liste_facture");
        $view->setVar('factures',       $factures["factures"]);
        $view->setVar('refFournisseur', $factures["refSupplier"]);
        $view->setVar('nomFournisseur', $nomFournisseur);
        $view->setVar('page', 'facture');   // info de la page pour charger l'aide

        return $view;
    }

    /**
     * Télécharge un fichier spécifique via l'API
     */
    public function telechargerFichier(): void
    {
        AuthService::checkAuthentication();
        $fichierUrl = htmlspecialchars($_GET['fichierUrl']) ?? '';
        $this->fournisseurService->telechargerFichierApi($fichierUrl);
    }
}