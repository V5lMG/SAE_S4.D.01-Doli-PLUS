<?php

namespace tests\controllers;

use controllers\FournisseurController;
use PHPUnit\Framework\TestCase;
use services\AuthService;
use services\FournisseurService;

class FournisseurControllerTest extends TestCase
{
    private FournisseurController $fournisseurController;
    private FournisseurService $fournisseurService;

    public function setUp(): void
    {
        parent::setUp();

        $this->fournisseurService = $this->createMock(FournisseurService::class);

        // Simule que checkAuthentication ne fait rien durant les tests
        AuthService::mockCheckAuthentication(fn() => null);

        $this->fournisseurController = new FournisseurController($this->fournisseurService);
    }

    public function testIndex()
    {
        $view = $this->fournisseurController->index();
        self::assertEquals("views/liste_fournisseur", $view->getRelativePath());
        self::assertEquals("fournisseur", $view->getVar("page"));
    }

    public function testIndexPalmares()
    {
        $this->fournisseurService->method('recupererListeCompletePalmares')->willReturn(["palmares_data"]);
        $view = $this->fournisseurController->indexPalmares();

        self::assertEquals("views/palmares_fournisseur", $view->getRelativePath());
        self::assertEquals(["palmares_data"], $view->getVar("listePalmares"));
        self::assertEquals(30, $view->getVar("top"));
        self::assertEquals("palmares", $view->getVar("page"));
    }

    public function testIndexListe()
    {
        $this->fournisseurService->method('recupererListeComplete')->willReturn(["fournisseurs"]);
        $this->fournisseurService->method('filtrerValeurs')->willReturn(["filtered_fournisseurs"]);

        $view = $this->fournisseurController->indexListe();

        self::assertEquals("views/liste_fournisseur", $view->getRelativePath());
        self::assertEquals(["filtered_fournisseurs"], $view->getVar("listeFournisseur"));
        self::assertEquals("fournisseur", $view->getVar("page"));
    }

    public function testIndexFactures()
    {
        $this->fournisseurService->method('factureFournisseur')->willReturn([
            "factures" => ["facture1", "facture2"],
            "refSupplier" => "REF123"
        ]);

        $_GET['nomFournisseur'] = "Test Fournisseur";
        $_GET['refFournisseur'] = "REF123";

        $view = $this->fournisseurController->indexFactures();

        self::assertEquals("views/liste_facture", $view->getRelativePath());
        self::assertEquals(["facture1", "facture2"], $view->getVar("factures"));
        self::assertEquals("REF123", $view->getVar("refFournisseur"));
        self::assertEquals("Test Fournisseur", $view->getVar("nomFournisseur"));
        self::assertEquals("facture", $view->getVar("page"));
    }

    public function testTelechargerFichier()
    {
        $this->fournisseurService
            ->expects($this->once())
            ->method('telechargerFichierApi')
            ->with("test_url");

        $_GET['fichierUrl'] = "test_url";

        $this->fournisseurController->telechargerFichier();
    }
}
