<?php


use PHPUnit\Framework\TestCase;
use application\DefaultComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

class DefaultComponentFactoryTest extends TestCase
{
    protected $componentFactory;

    // Cette méthode est appelée avant chaque test pour initialiser l'objet que l'on va tester
    protected function setUp(): void
    {
        // GIVEN : On crée une nouvelle instance de DefaultComponentFactory pour chaque test
        $this->componentFactory = new DefaultComponentFactory();
    }

    /**
     * Test de la méthode buildControllerByName pour un contrôleur existant.
     *
     * @covers DefaultComponentFactory::buildControllerByName
     */
    public function testBuildControllerByNameExistingController()
    {
        // GIVEN : On a un nom de contrôleur valide "Home" (on s'attend à ce qu'il corresponde à un contrôleur existant dans notre factory)
        $controllerName = 'Home';

        // WHEN : On appelle la méthode buildControllerByName avec le nom de contrôleur "Home"
        $controller = $this->componentFactory->buildControllerByName($controllerName);

        // THEN : On s'attend à ce que la méthode retourne une instance du contrôleur AuthController
        // Cela vérifie que la méthode buildControllerByName crée le bon contrôleur
        $this->assertInstanceOf(\controllers\AuthController::class, $controller);
    }

    /**
     * Test de la méthode buildControllerByName pour un contrôleur inexistant.
     *
     * @covers DefaultComponentFactory::buildControllerByName
     */
    public function testBuildControllerByNameNonExistingController()
    {
        // GIVEN : On a un nom de contrôleur inexistant "NonExistentController"
        $controllerName = 'NonExistentController';

        // WHEN & THEN : On s'attend à ce que la méthode lève une exception NoControllerAvailableForNameException
        // Cela vérifie que la méthode gère correctement les cas où le contrôleur demandé n'existe pas
        $this->expectException(NoControllerAvailableForNameException::class);
        $this->componentFactory->buildControllerByName($controllerName);
    }

    /**
     * Test de la méthode buildServiceByName pour un service existant.
     *
     * @covers DefaultComponentFactory::buildServiceByName
     */
    public function testBuildServiceByNameExistingService()
    {
        // GIVEN : On a un nom de service valide "Auth" (le service AuthService doit être créé pour ce nom)
        $serviceName = 'Auth';

        // WHEN : On appelle la méthode buildServiceByName avec le nom du service "Auth"
        $service = $this->componentFactory->buildServiceByName($serviceName);

        // THEN : On s'attend à ce que la méthode retourne une instance du service AuthService
        // Cela vérifie que la méthode buildServiceByName crée le bon service
        $this->assertInstanceOf(\services\AuthService::class, $service);
    }

    /**
     * Test de la méthode buildServiceByName pour un service inexistant.
     *
     * @covers DefaultComponentFactory::buildServiceByName
     */
    public function testBuildServiceByNameNonExistingService()
    {
        // GIVEN : On a un nom de service inexistant "NonExistentService"
        $serviceName = 'NonExistentService';

        // WHEN & THEN : On s'attend à ce que la méthode lève une exception NoServiceAvailableForNameException
        // Cela vérifie que la méthode gère correctement les cas où le service demandé n'existe pas
        $this->expectException(NoServiceAvailableForNameException::class);
        $this->componentFactory->buildServiceByName($serviceName);
    }
}
