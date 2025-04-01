<?php

use Mockery;
use PHPUnit\Framework\TestCase;
use controllers\AccueilController;
use services\AuthService;
use yasmf\View;

class AccueilControllerTest extends TestCase
{
    private $authService;
    private $accueilController;

    protected function setUp(): void
    {
        // Mock du service d'authentification
        $this->authService = Mockery::mock(AuthService::class);
        $this->accueilController = new AccueilController($this->authService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testIndexSansUrlSaisie()
    {
        // Simulation de l'authentification sans surcharge de la classe
        $authServiceMock = Mockery::mock('services\AuthService');
        $authServiceMock->shouldReceive('checkAuthentication')->once();

        // On injecte le mock dans le contrôleur
        $this->accueilController->setAuthService($authServiceMock);

        // Sauvegarde et réinitialisation de la session avant le test
        $sessionBackup = $_SESSION ?? [];
        $_SESSION = ["url_saisie" => ''];

        // Appel de la méthode
        $view = $this->accueilController->index();

        // Vérifications
        $this->assertEquals("views/accueil", $view->getRelativePath());
        $this->assertEquals($this->accueilController, $view->getVar('controller'));
        $this->assertEquals('accueil', $view->getVar('page'));

        // Restauration de la session après le test
        $_SESSION = $sessionBackup;
    }



    public function testIndexAvecUrlSaisieExistante()
    {
        // Simulation de l'authentification
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simuler une URL saisie dans la session
        $_SESSION["url_saisie"] = 'https://exemple.com';

        // Simuler que l'URL existe déjà dans la liste
        $this->authService->shouldReceive('getUrlFichier')
            ->once()
            ->andReturn(['https://exemple.com']);

        // Appel de la méthode
        $view = $this->accueilController->index();

        // Vérifications
        $this->assertEquals("views/accueil", $view->getRelativePath());
        $this->assertEquals($this->accueilController, $view->getVar('controller'));
        $this->assertEquals('accueil', $view->getVar('page'));
        $this->assertNull($view->getVar('url'));
    }

    public function testIndexAvecUrlSaisieNonExistante()
    {
        // Simulation de l'authentification
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simuler une URL saisie dans la session
        $_SESSION["url_saisie"] = 'https://nouvelle-url.com';

        // Simuler que l'URL n'existe pas
        $this->authService->shouldReceive('getUrlFichier')
            ->once()
            ->andReturn(['https://autre-url.com']);

        // Appel de la méthode
        $view = $this->accueilController->index();

        // Vérifications
        $this->assertEquals("views/accueil", $view->getRelativePath());
        $this->assertEquals($this->accueilController, $view->getVar('controller'));
        $this->assertEquals('accueil', $view->getVar('page'));
        $this->assertEquals('https://nouvelle-url.com', $view->getVar('url'));
    }

    public function testAddUrl()
    {
        // Simulation d'une URL envoyée en POST
        $_POST["new_url"] = "https://nouveau-site.com";

        // Simulation de la méthode setUrlFichier
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('setUrlFichier')
            ->once()
            ->with("https://nouveau-site.com");

        // On capture la sortie pour vérifier la redirection
        $this->expectOutputRegex('/Location: index\.php\?controller=Accueil&action=index/');

        // Appel de la méthode
        $this->accueilController->addUrl();
    }

    public function testUrlExisteAvecUrlExistante()
    {
        // Simulation de la récupération des URLs
        $this->authService->shouldReceive('getUrlFichier')
            ->once()
            ->andReturn(['https://exemple.com', 'https://test.com']);

        // Vérification de l'existence d'une URL
        $this->assertTrue($this->accueilController->urlExiste('https://exemple.com'));
    }

    public function testUrlExisteAvecUrlInexistante()
    {
        // Simulation de la récupération des URLs
        $this->authService->shouldReceive('getUrlFichier')
            ->once()
            ->andReturn(['https://existant.com', 'https://autre.com']);

        // Vérification de l'existence d'une URL qui n'est pas dans la liste
        $this->assertFalse($this->accueilController->urlExiste('https://inconnu.com'));
    }
}
