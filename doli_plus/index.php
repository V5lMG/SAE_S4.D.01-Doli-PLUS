<?php
const PREFIX_TO_RELATIVE_PATH = "/doli_plus";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use application\DefaultComponentFactory;
use yasmf\Router;

$router = new Router(new DefaultComponentFactory()) ;
try {
    $router->route(PREFIX_TO_RELATIVE_PATH);
} catch (\yasmf\NoControllerAvailableForNameException $e) {
    echo "La page n'est plus fonctionnelle ! Veuillez réessayer plus tard.";
}
