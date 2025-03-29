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

        var_dump("SetUp called");
        // Création des mocks pour les services
        $this->fournisseurService = Mockery::mock(FournisseurService::class);

        // Création du contrôleur avec un FournisseurService mocké
        $this->fournisseurController = new FournisseurController($this->fournisseurService);

        // Création d'un mock pour la vue
        $this->view = Mockery::mock(View::class);
    }

    public function testIndex()
    {
        // Nous nous assurons que la méthode checkAuthentication est appelée
        $authServiceMock = Mockery::mock(AuthService::class);
        $authServiceMock->shouldReceive('checkAuthentication')->once();

        // On teste la méthode index
        $view = $this->fournisseurController->index();

        // Vérification que la vue retournée est bien celle attendue
        $this->assertEquals("views/liste_fournisseur", $view->getRelativePath());
        $this->assertNotNull($view->getVar('page')); // Vérifie si la variable 'page' est bien définie
        $this->assertEquals('fournisseur', $view->getVar('page')); // Vérifie que 'page' a la bonne valeur
    }

    public function testIndexPalmares()
    {
        // Création d'un mock pour la méthode checkAuthentication
        $authServiceMock = Mockery::mock(AuthService::class);
        $authServiceMock->shouldReceive('checkAuthentication')->once();

        // Simuler les paramètres de la requête HTTP
        $httpHelperMock = Mockery::mock(HttpHelper::class);
        $httpHelperMock->shouldReceive('getParam')
            ->with('date_debut')->andReturn('2022-01-01')
            ->with('date_fin')->andReturn('2022-12-31')
            ->with('top')->andReturn(30);

        // Simulation de la méthode recupererListeCompletePalmares
        $this->fournisseurService->shouldReceive('recupererListeCompletePalmares')
            ->andReturn(['palmares data']); // Remplace par une valeur attendue

        // Appel à la méthode indexPalmares
        $view = $this->fournisseurController->indexPalmares();

        // Vérification de la vue retournée
        $this->assertEquals("views/palmares_fournisseur", $view->getRelativePath());
        $this->assertEquals('palmares', $view->getVar('page'));
        $this->assertEquals(30, $view->getVar('top'));
    }

    public function testIndexListe()
    {
        // Création d'un mock pour la méthode checkAuthentication
        $authServiceMock = Mockery::mock(AuthService::class);
        $authServiceMock->shouldReceive('checkAuthentication')->once();

        // Simuler les paramètres de la session et les paramètres HTTP
        $_SESSION['nom'] = 'testNom';
        $_SESSION['numTel'] = '123456789';

        // Simulation de la méthode filtrerValeurs du service
        $this->fournisseurService->shouldReceive('filtrerValeurs')
            ->andReturn(['filteredData']); // Remplace par une valeur attendue

        // Appel à la méthode indexListe
        $view = $this->fournisseurController->indexListe();

        // Vérification de la vue retournée
        $this->assertEquals("views/liste_fournisseur", $view->getRelativePath());
        $this->assertEquals('fournisseur', $view->getVar('page'));
    }

    public function testIndexFactures()
    {
        // Création d'un mock pour la méthode checkAuthentication
        $authServiceMock = Mockery::mock(AuthService::class);
        $authServiceMock->shouldReceive('checkAuthentication')->once();

        // Simuler les paramètres HTTP GET
        $_GET['nomFournisseur'] = 'FournisseurTest';
        $_GET['refFournisseur'] = 'REF123';

        // Simulation de la méthode factureFournisseur du service
        $this->fournisseurService->shouldReceive('factureFournisseur')
            ->andReturn(['factures' => 'testFactures', 'refSupplier' => 'REF123']); // Remplace par une valeur attendue

        // Appel à la méthode indexFactures
        $view = $this->fournisseurController->indexFactures();

        // Vérification de la vue retournée
        $this->assertEquals("views/liste_facture", $view->getRelativePath());
        $this->assertEquals('facture', $view->getVar('page'));
        $this->assertEquals('testFactures', $view->getVar('factures'));
    }

    public function testTelechargerFichier()
    {
        // Simuler la vérification de l'authentification
        $authServiceMock = Mockery::mock(AuthService::class);
        $authServiceMock->shouldReceive('checkAuthentication')->once();

        // Simuler la méthode telechargerFichierApi
        $this->fournisseurService->shouldReceive('telechargerFichierApi')
            ->with('testFichier.pdf')
            ->once();

        // Simulation de la requête GET
        $_GET['fichierUrl'] = 'testFichier.pdf';

        // Appel à la méthode telechargerFichier
        $this->fournisseurController->telechargerFichier();
    }

    // Méthode pour fermer Mockery après les tests
    public function tearDown(): void
    {
        Mockery::close(); // Libérer les mocks
        parent::tearDown();
    }
}

