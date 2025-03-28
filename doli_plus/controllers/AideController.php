<?php

namespace controllers;

use yasmf\View;

class AideController
{
    public function accueil(): View
    {
        return new View("views/aide/aide_accueil");
    }

    public function facture(): View
    {
        return new View("views/aide/aide_facture");
    }

    public function fournisseur(): View
    {
        return new View("views/aide/aide_fournisseur");
    }

    public function noteFrais(): View
    {
        return new View("views/aide/aide_noteFrais");
    }

    public function palmares(): View
    {
        return new View("views/aide/aide_palmares");
    }

    public function statistiques(): View
    {
        return new View("views/aide/aide_statistiques");
    }

    public function default(): View
    {
        return new View("views/aide/aide_default");
    }
}
