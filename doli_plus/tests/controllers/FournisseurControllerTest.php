<?php

namespace controllers;

use PHPUnit\Framework\TestCase;
use Mockery;
use services\FournisseurService;
use services\AuthService;
use yasmf\View;
use yasmf\HttpHelper;

class FournisseurControllerTest extends TestCase
{
    private FournisseurController $fournisseurController;
    private FournisseurService $fournisseurService;
    private View $view;

    public function setUp(): void
    {
        parent::setUp();

        // Mocks initialisés dans chaque méthode de test
        $this->fournisseurService = Mockery::mock(FournisseurService::class);
        $this->fournisseurController = new FournisseurController($this->fournisseurService);
        $this->view = Mockery::mock(View::class);
        session_start();
    }

    public function testIndex()
    {
        // Given: Simulation de l'authentification
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // When: Appel à la méthode index
        $view = $this->fournisseurController->index();

        // Then: Vérification de la vue retournée
        $this->assertEquals("views/liste_fournisseur", $view->getRelativePath());
        $this->assertNotNull($view->getVar('page'));
        $this->assertEquals('fournisseur', $view->getVar('page'));
    }

    public function testIndexPalmares()
    {
        // Given: Simulation de l'authentification et des paramètres HTTP
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        Mockery::mock('alias:yasmf\HttpHelper')
            ->shouldReceive('getParam')
            ->with('date_debut')->andReturn('2022-01-01')
            ->shouldReceive('getParam')
            ->with('date_fin')->andReturn('2022-12-31')
            ->shouldReceive('getParam')
            ->with('top')->andReturn(30);

        // Simulation de la méthode de service
        $this->fournisseurService->shouldReceive('recupererListeCompletePalmares')
            ->andReturn(['palmares data']);

        // When: Appel à la méthode indexPalmares
        $view = $this->fournisseurController->indexPalmares();

        // Then: Vérification de la vue retournée
        $this->assertEquals("views/palmares_fournisseur", $view->getRelativePath());
        $this->assertEquals('palmares', $view->getVar('page'));
        $this->assertEquals(30, $view->getVar('top'));
    }

    public function testIndexListe()
    {
        // Given: Simulation de l'authentification et des paramètres session
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simulation de la session
        $_SESSION['nom'] = 'testNom';
        $_SESSION['numTel'] = '123456789';
        $_SESSION['adresse'] = '123 rue test';
        $_SESSION['codePostal'] = '75001';

        // Simulation de la méthode recupererListeComplete
        $this->fournisseurService->shouldReceive('recupererListeComplete')
            ->andReturn(['fournisseur1', 'fournisseur2']);

        // Simulation de la méthode filtrerValeurs avec des valeurs non nulles
        $this->fournisseurService->shouldReceive('filtrerValeurs')
            ->with(
                ['fournisseur1', 'fournisseur2'], // Liste non triée
                'testNom',  // Nom
                '123456789', // Numéro de téléphone
                '123 rue test', // Adresse
                '75001'  // Code postal
            )
            ->andReturn(['filteredData']);

        // When: Appel à la méthode indexListe
        $view = $this->fournisseurController->indexListe();

        // Then: Vérification de la vue retournée
        $this->assertEquals("views/liste_fournisseur", $view->getRelativePath());
        $this->assertEquals('fournisseur', $view->getVar('page'));
        $this->assertEquals(['filteredData'], $view->getVar('listeFournisseur'));
    }

    public function testIndexFactures()
    {
        // Given: Simulation de l'authentification et des paramètres GET
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        $_GET['nomFournisseur'] = 'FournisseurTest';
        $_GET['refFournisseur'] = 'REF123';

        $this->fournisseurService->shouldReceive('factureFournisseur')
            ->andReturn(['factures' => 'testFactures', 'refSupplier' => 'REF123']);

        // When: Appel à la méthode indexFactures
        $view = $this->fournisseurController->indexFactures();

        // Then: Vérification de la vue retournée
        $this->assertEquals("views/liste_facture", $view->getRelativePath());
        $this->assertEquals('facture', $view->getVar('page'));
        $this->assertEquals('testFactures', $view->getVar('factures'));
    }

    public function testTelechargerFichier()
    {
        // Given: Simulation de l'authentification
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simulation de la méthode telechargerFichierApi
        $this->fournisseurService->shouldReceive('telechargerFichierApi')
            ->with('testFichier.pdf')
            ->once();

        $_GET['fichierUrl'] = 'testFichier.pdf';

        // When: Appel à la méthode telechargerFichier
        $this->fournisseurController->telechargerFichier();

        // Then: Vérification qu'aucune erreur n'a été levée
        $this->assertTrue(true); // Test passe si aucune exception n'est levée
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
