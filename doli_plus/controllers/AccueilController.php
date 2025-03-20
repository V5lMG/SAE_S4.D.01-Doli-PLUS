<?php
namespace controllers;

use yasmf\View;
use services\AuthService;

class AccueilController
{
    public function index(): View
    {
        AuthService::checkAuthentication();

        return new View("views/accueil");
    }
}