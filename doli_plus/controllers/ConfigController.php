<?php
namespace controllers;

use yasmf\View;

class ConfigController
{
    public function url(): View
    {
        return new View("views/config_url");
    }
}