<?php
namespace controllers;

use yasmf\View;

class MenuController
{
    public function index(): View
    {
        return new View("views/menu");
    }
}
