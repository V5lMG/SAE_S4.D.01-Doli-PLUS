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
     * Affiche la page d'achat fournisseur.
     * Vérifie que l'utilisateur est authentifié avant d'afficher la vue.
     *
     * @return View La vue de la page d'achat fournisseur.
     */
    public function indexAchat(): View
    {
        AuthService::checkAuthentication();
        return new View("views/achat_fournisseur");
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
        return $view;
    }

    /**
     * Affiche la liste des notes de frais filtrées.
     * Applique les filtres récupérés via les paramètres HTTP et calcule les totaux.
     *
     * @return View La vue filtrée des notes de frais avec les totaux.
     */
    public function indexListe(): View
    {
        AuthService::checkAuthentication();

        // Récupérer la liste des fournisseurs
        $listeFournisseurNonTrie = $this->fournisseurService->recupererListeComplete();

        // Récupération des paramètres de filtre
        $nom        = HttpHelper::getParam('nom', '');
        $numTel     = HttpHelper::getParam('adresse', '');
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
        $view = new View("views/achat_fournisseur");
        $view->setVar('listeFournisseur', $listeFournisseur);
        return $view;
    }

}