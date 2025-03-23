<?php
/*
 * Chemin relatif vers le répertoire principal de l'application.
 */
const PREFIX_TO_RELATIVE_PATH = "/doli_plus";
/*
 * Inclusion du fichier d'autoload de Composer pour charger automatiquement les dépendances.
 */
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use application\DefaultComponentFactory;
use yasmf\Router;

/*
 * Création d'une instance de Router avec un composant par défaut.
 */
$router = new Router(new DefaultComponentFactory()) ;

try {
    /*
     * Exécution du routage pour le chemin relatif défini.
     */
    $router->route(PREFIX_TO_RELATIVE_PATH);
} catch (\yasmf\NoControllerAvailableForNameException $e) {
    /*
     * Gestion des erreurs si aucune route ne correspond au nom du contrôleur.
     */
    echo "La page n'est plus fonctionnelle ! Veuillez réessayer plus tard.";
}
