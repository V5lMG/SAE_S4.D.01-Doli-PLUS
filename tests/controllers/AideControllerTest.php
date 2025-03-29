<?php

namespace controllers;

use PHPUnit\Framework\TestCase;

class AideControllerTest extends TestCase
{
    private AideController $aideController;

    public function setUp(): void
    {
        parent::setUp();
        // Initialisation du contrôleur à tester
        $this->aideController = new AideController();
    }

    public function testAccueil()
    {
        $view = $this->aideController->accueil();
        self::assertEquals("views/aide/aide_accueil", $view->getRelativePath());
    }

    public function testFacture()
    {
        $view = $this->aideController->facture();
        self::assertEquals("views/aide/aide_facture", $view->getRelativePath());
    }

    public function testFournisseur()
    {
        $view = $this->aideController->fournisseur();
        self::assertEquals("views/aide/aide_fournisseur", $view->getRelativePath());
    }

    public function testNoteFrais()
    {
        $view = $this->aideController->noteFrais();
        self::assertEquals("views/aide/aide_noteFrais", $view->getRelativePath());
    }

    public function testPalmares()
    {
        $view = $this->aideController->palmares();
        self::assertEquals("views/aide/aide_palmares", $view->getRelativePath());
    }

    public function testStatistiques()
    {
        $view = $this->aideController->statistiques();
        self::assertEquals("views/aide/aide_statistiques", $view->getRelativePath());
    }

    public function testDefault()
    {
        $view = $this->aideController->default();
        self::assertEquals("views/aide/aide_default", $view->getRelativePath());
    }
}
