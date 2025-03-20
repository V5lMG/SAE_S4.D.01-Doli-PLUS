<?php
namespace controllers;

use yasmf\View;

class AccueilController
{
    public function index(): View
    {
        return new View("views/accueil");
    }
}