<?php
namespace controllers;

use yasmf\View;

class UrlController
{
    public function index(): View
    {
        return new View("views/config_url");
    }
}