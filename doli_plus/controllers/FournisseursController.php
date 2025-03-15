<?php
namespace controllers;

use yasmf\View;

class FournisseursController
{
    public function index(): View
    {
        return new View("views/fournisseurs");
    }
}
