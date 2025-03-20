<?php
namespace controllers;

use services\AuthService;
use yasmf\View;

class UrlController
{
    public function index(): View
    {
        AuthService::checkAuthentication();

        return new View("views/config_url");
    }
}