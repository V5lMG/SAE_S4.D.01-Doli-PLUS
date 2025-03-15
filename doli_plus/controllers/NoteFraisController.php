<?php
namespace controllers;

use yasmf\View;

class NoteFraisController
{
    public function index(): View
    {
        return new View("views/note_frais");
    }
}
