<?php
namespace controllers;

use services\AuthService;
use yasmf\View;

class FournisseurController
{
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
     * Affiche la page du palmarès fournisseur.
     * Vérifie que l'utilisateur est authentifié avant d'afficher la vue.
     *
     * @return View La vue du palmarès fournisseur.
     */
    public function indexPalmares(): View
    {
        AuthService::checkAuthentication();
        return new View("views/palmares_fournisseur");
    }
}