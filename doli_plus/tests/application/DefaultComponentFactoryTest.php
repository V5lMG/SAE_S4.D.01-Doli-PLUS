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

    /**
     * Test que les services respectent le pattern Singleton.
     *
     * @covers DefaultComponentFactory::buildAuthService
     * @covers DefaultComponentFactory::buildNoteFraisService
     * @covers DefaultComponentFactory::buildFournisseurService
     */
    public function testServiceIsSingleton()
    {
        $authService1 = $this->componentFactory->buildServiceByName("Auth");
        $authService2 = $this->componentFactory->buildServiceByName("Auth");
        $this->assertSame($authService1, $authService2, "AuthService ne respecte pas le singleton.");

        $noteFraisService1 = $this->componentFactory->buildServiceByName("NoteFrais");
        $noteFraisService2 = $this->componentFactory->buildServiceByName("NoteFrais");
        $this->assertSame($noteFraisService1, $noteFraisService2, "NoteFraisService ne respecte pas le singleton.");

        $fournisseurService1 = $this->componentFactory->buildServiceByName("Fournisseur");
        $fournisseurService2 = $this->componentFactory->buildServiceByName("Fournisseur");
        $this->assertSame($fournisseurService1, $fournisseurService2, "FournisseurService ne respecte pas le singleton.");
    }

    /**
     * Test de la méthode buildServiceByName pour tous les services existants.
     *
     * @covers DefaultComponentFactory::buildServiceByName
     */
    public function testBuildServiceByNameForAllServices()
    {
        $services = [
            "Auth"        => \services\AuthService::class,
            "NoteFrais"   => \services\NoteFraisService::class,
            "Fournisseur" => \services\FournisseurService::class
        ];

        foreach ($services as $name => $expectedClass) {
            $service = $this->componentFactory->buildServiceByName($name);
            $this->assertInstanceOf($expectedClass, $service, "Le service '$name' n'est pas du bon type.");
        }
    }

    /**
     * Test de la méthode buildControllerByName pour le contrôleur NoteFrais.
     *
     * @covers DefaultComponentFactory::buildControllerByName
     */
    public function testBuildNoteFraisController()
    {
        // GIVEN : Un nom de contrôleur valide "NoteFrais"
        $controllerName = 'NoteFrais';

        // WHEN : On appelle la méthode buildControllerByName
        $controller = $this->componentFactory->buildControllerByName($controllerName);

        // THEN : On vérifie que c'est bien une instance de NoteFraisController
        $this->assertInstanceOf(\controllers\NoteFraisController::class, $controller);
    }

    /**
     * Test de la méthode buildControllerByName pour le contrôleur Accueil.
     *
     * @covers DefaultComponentFactory::buildControllerByName
     */
    public function testBuildAccueilController()
    {
        // GIVEN : Un nom de contrôleur valide "Accueil"
        $controllerName = 'Accueil';

        // WHEN : On appelle la méthode buildControllerByName
        $controller = $this->componentFactory->buildControllerByName($controllerName);

        // THEN : On vérifie que c'est bien une instance de AccueilController
        $this->assertInstanceOf(\controllers\AccueilController::class, $controller);
    }
}
