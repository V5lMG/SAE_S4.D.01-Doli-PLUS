<?php
namespace controllers;

use services\AuthService;
use yasmf\View;

class FournisseurController
{
    public function indexAchat(): View
    {
        AuthService::checkAuthentication();
        return new View("views/achat_fournisseur");
    }

    public function indexPalmares(): View
    {
        AuthService::checkAuthentication();
        return new View("views/palmares_fournisseur");
    }
}
