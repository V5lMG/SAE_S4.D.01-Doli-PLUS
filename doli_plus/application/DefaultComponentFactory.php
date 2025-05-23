<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
namespace application;

use controllers\AideController;
use controllers\FournisseurController;
use controllers\AccueilController;
use controllers\NoteFraisController;
use controllers\AuthController;

use services\AuthService;
use services\FournisseurService;
use services\NoteFraisService;

use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

/**
 * DefaultComponentFactory est une classe responsable de la création dynamique des
 * contrôleurs et des services nécessaires à l'application.
 * Elle implémente l'interface ComponentFactory pour suivre une architecture basée sur
 * l'injection de dépendances, où les composants (contrôleurs, services) sont créés
 * en fonction de leur nom et sont utilisés par le reste de l'application.
 *
 * Cette classe utilise un modèle de conception Singleton pour certains services,
 * afin de ne créer qu'une seule instance pour chaque service.
 *
 * @author ValMG
 * @author Xavier Taborda Rodrigo
 * @author Thierry Erwan
 * @author Cambon Mathias
 * Pas de copyright, ni de droit d'auteur
 */
class DefaultComponentFactory implements ComponentFactory
{
    private ?AuthService $authService = null;

    private ?NoteFraisService $noteFraisService = null;

    private ?FournisseurService $fournisseurService = null;

    /**
     * Méthode permettant de créer un contrôleur en fonction de son nom.
     *
     * @param string $controller_name Le nom du contrôleur à instancier.
     * @return mixed Le contrôleur correspondant au nom fourni.
     * @throws NoControllerAvailableForNameException Si aucun contrôleur n'est trouvé pour le nom donné.
     */
    public function buildControllerByName(string $controller_name): mixed
    {
        return match ($controller_name) {
            "Home"        => $this->buildAuthController(),
            "Accueil"     => $this->buildAccueilController(),

            "NoteFrais"   => $this->buildNoteFraisController(),
            "Fournisseur" => $this->buildFournisseurController(),

            "Aide"        => new AideController(),

            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    /**
     * Méthode permettant de créer un service en fonction de son nom.
     *
     * @param string $service_name Le nom du service à instancier.
     * @return mixed Le service correspondant au nom fourni.
     * @throws NoServiceAvailableForNameException Si aucun service n'est trouvé pour le nom donné.
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return match($service_name) {
            "Auth" => $this->buildAuthService(),
            "NoteFrais" => $this->buildNoteFraisService(),
            "Fournisseur" => $this->buildFournisseurService(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    // ---------------------------------------------Authentification----------------------------------------------------//

    /**
     * Méthode privée pour instancier le service d'authentification.
     * Le service est créé une seule fois et réutilisé (Singleton).
     *
     * @return AuthService Le service d'authentification.
     */
    private function buildAuthService(): AuthService
    {
        if ($this->authService == null) {
            $this->authService = new AuthService();
        }
        return $this->authService;
    }

    /**
     * Méthode privée pour créer le contrôleur d'authentification.
     *
     * @return AuthController Le contrôleur d'authentification.
     */
    private function buildAuthController(): AuthController
    {
        return new AuthController($this->buildAuthService());
    }

    // ---------------------------------------------Note de Frais----------------------------------------------------//

    /**
     * Méthode privée pour instancier le service des notes de frais.
     * Le service est créé une seule fois et réutilisé (Singleton).
     *
     * @return NoteFraisService Le service de la gestion des notes de frais.
     */
    private function buildNoteFraisService(): NoteFraisService
    {
        if ($this->noteFraisService == null) {
            $this->noteFraisService = new NoteFraisService();
        }
        return $this->noteFraisService;
    }

    /**
     * Méthode privée pour créer le contrôleur des notes de frais.
     *
     * @return NoteFraisController Le contrôleur des notes de frais.
     */
    private function buildNoteFraisController(): NoteFraisController
    {
        return new NoteFraisController($this->buildNoteFraisService());
    }

    // ---------------------------------------------Fournisseur-------------------------------------------------//

    /**
     * Méthode privée pour instancier le service des fournisseurs.
     * Le service est créé une seule fois et réutilisé (Singleton).
     *
     * @return FournisseurService Le service de la gestion des notes de frais.
     */
    private function buildFournisseurService(): FournisseurService
    {
        if ($this->fournisseurService == null) {
            $this->fournisseurService = new FournisseurService();
        }
        return $this->fournisseurService;
    }

    /**
     * Méthode privée pour créer le contrôleur des fournisseurs.
     *
     * @return FournisseurController Le contrôleur des notes de frais.
     */
    private function buildFournisseurController(): FournisseurController
    {
        return new FournisseurController($this->buildFournisseurService());
    }




    // ---------------------------------------------Accueil----------------------------------------------------//

    /**
     * Méthode privée pour créer le contrôleur de l'accueil
     *
     * @return AccueilController Le contrôleur d'authentification.
     */
    private function buildAccueilController(): AccueilController
    {
        return new AccueilController($this->buildAuthService());
    }
}