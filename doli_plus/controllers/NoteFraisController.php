<?php
namespace controllers;

use yasmf\View;

class NoteFraisController
{
    public function indexListe(): View
    {
        return new View("views/liste_note_frais");
    }

    public function indexListeBis(): View
    {
        return new View("views/liste_note_frais_bis");
    }

    public function indexStatistique(): View
    {
        return new View("views/statistique_note_frais");
    }
}
