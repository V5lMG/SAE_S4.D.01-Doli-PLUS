<?php
const PREFIX_TO_RELATIVE_PATH = "/doli_plus";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use application\DefaultComponentFactory;
use yasmf\DataSource;
use yasmf\Router;

//$data_source = null; // STUB

$router = new Router(new DefaultComponentFactory()) ;
try {
    $router->route(PREFIX_TO_RELATIVE_PATH);
} catch (\yasmf\NoControllerAvailableForNameException $e) {
    echo "LA PAGE EST DOWN !!!";
}
