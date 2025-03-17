<?php
namespace controllers;

use yasmf\View;

class FournisseurController
{
    public function indexAchat(): View
    {
        return new View("views/achat_fournisseur");
    }

    public function indexPalmares(): View
    {
        return new View("views/palmares_fournisseur");
    }
}
