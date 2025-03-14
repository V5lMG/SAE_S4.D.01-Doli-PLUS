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

use services\AuthService;
use controllers\HomeController;
use controllers\DisconnectController;
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

class DefaultComponentFactory implements ComponentFactory
{
    private ?AuthService $authService = null;

    public function buildControllerByName(string $controller_name): mixed
    {
        return match ($controller_name) {
            "Home" => $this->buildHomeController(),
            "Disconnect" => $this->buildDisconnectController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    public function buildServiceByName(string $service_name): mixed
    {
        return match($service_name) {
            "Auth" => $this->buildAuthService(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    private function buildAuthService(): AuthService
    {
        if ($this->authService == null) {
            $this->authService = new AuthService();
        }
        return $this->authService;
    }

    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController($this->buildAuthService());
    }

    /**
     * @return DisconnectController
     */
    private function buildDisconnectController(): DisconnectController
    {
        return new DisconnectController($this->buildAuthService());
    }
}
