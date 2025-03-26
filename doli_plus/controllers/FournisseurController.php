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
     * @param FournisseurService $FournisseurService Le service de gestion des fournisseurs.
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
        return new View("views/liste_fournisseur");
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
        $listPalmares = [];

        $listPalmares = $this->fournisseurService->recupererListeCompletePalmares();

        // Passer les données à la vue
        $view = new View("views/palmares_fournisseur");
        $view->setVar('listePalmares', $listPalmares);
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

        // Récupérer la liste des fournisseurs
        $listeFournisseurNonTrie = $this->fournisseurService->recupererListeComplete();

        // Récupération des paramètres de filtre
        $nom        = HttpHelper::getParam('nom', '');
        $numTel     = HttpHelper::getParam('numTel', '');
        $adresse    = HttpHelper::getParam('adresse', '');
        $codePostal = HttpHelper::getParam('codePostal', '');

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
        var_dump($factures);
        // Passer les variables à la vue
        $view = new View("views/liste_facture");
        $view->setVar('factures',       $factures["factures"]);
        $view->setVar('refFournisseur', $factures["refSupplier"]);
        $view->setVar('nomFournisseur', $nomFournisseur);

        return $view;
    }
}