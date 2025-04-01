<?php

namespace controllers;

use PHPUnit\Framework\TestCase;
use Mockery;
use services\NoteFraisService;
use services\AuthService;
use yasmf\View;
use yasmf\HttpHelper;

class NoteFraisControllerTest extends TestCase
{
    private NoteFraisController $noteFraisController;
    private NoteFraisService $noteFraisService;
    private View $view;

    public function setUp(): void
    {
        parent::setUp();

        // Initialisation des mocks
        $this->noteFraisService = Mockery::mock(NoteFraisService::class);
        $this->noteFraisController = new NoteFraisController($this->noteFraisService);
        $this->view = Mockery::mock(View::class);
        session_start();
    }

    public function testIndex()
    {
        // Authentification simulée
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Appel de la méthode
        $view = $this->noteFraisController->index();

        // Vérifications
        $this->assertEquals("views/liste_note_frais", $view->getRelativePath());
    }

    public function testIndexListeAvecFiltrage()
    {
        // Authentification simulée
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simulation des paramètres de filtre
        Mockery::mock('alias:yasmf\HttpHelper')
            ->shouldReceive('getParam')->with('employe')->andReturn('TestEmploye')
            ->shouldReceive('getParam')->with('type')->andReturn('Transport')
            ->shouldReceive('getParam')->with('reference')->andReturn('REF123')
            ->shouldReceive('getParam')->with('date_debut')->andReturn('2024-01-01')
            ->shouldReceive('getParam')->with('date_fin')->andReturn('2024-12-31')
            ->shouldReceive('getParam')->with('etat')->andReturn('Validé')
            ->shouldReceive('getParam')->with('afficherTous')->andReturn(false);

        // Simulation des données renvoyées par le service
        $this->noteFraisService->shouldReceive('recupererListeComplete')
            ->once()
            ->andReturn([
                ['montant_ht' => 100, 'montant_tva' => 20, 'montant_ttc' => 120],
                ['montant_ht' => 200, 'montant_tva' => 40, 'montant_ttc' => 240]
            ]);

        $this->noteFraisService->shouldReceive('filtrerValeurs')
            ->once()
            ->andReturn([
                'notes' => [['montant_ht' => 100, 'montant_tva' => 20, 'montant_ttc' => 120]],
                'totaux' => [
                    'nombre_note' => 1,
                    'montant_ht_total' => 100,
                    'montant_tva_total' => 20,
                    'montant_ttc_total' => 120
                ]
            ]);

        // Appel de la méthode
        $view = $this->noteFraisController->indexListe();

        // Vérifications
        $this->assertEquals("views/liste_note_frais", $view->getRelativePath());
        $this->assertEquals(1, $view->getVar('totaux')['nombre_note']);
        $this->assertEquals(100, $view->getVar('totaux')['montant_ht_total']);
    }
    public function testIndexListeSansFiltrage()
    {
        // Authentification simulée
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simulation des paramètres HTTP
        Mockery::mock('alias:yasmf\HttpHelper')
            ->shouldReceive('getParam')->with('afficherTous')->andReturn(true)
            ->shouldReceive('getParam')->with('employe')->andReturn(null)
            ->shouldReceive('getParam')->with('type')->andReturn(null)
            ->shouldReceive('getParam')->with('reference')->andReturn(null)
            ->shouldReceive('getParam')->with('date_debut')->andReturn(null)
            ->shouldReceive('getParam')->with('date_fin')->andReturn(null)
            ->shouldReceive('getParam')->with('etat')->andReturn(null);

        // Simulation des données de service
        $this->noteFraisService->shouldReceive('recupererListeComplete')
            ->once()
            ->andReturn([
                ['montant_ht' => 150, 'montant_tva' => 30, 'montant_ttc' => 180],
                ['montant_ht' => 250, 'montant_tva' => 50, 'montant_ttc' => 300]
            ]);

        // Appel de la méthode
        $view = $this->noteFraisController->indexListe();

        // Vérifications
        $this->assertEquals("views/liste_note_frais", $view->getRelativePath());
        $this->assertEquals(2, $view->getVar('totaux')['nombre_note']);
        $this->assertEquals(400, $view->getVar('totaux')['montant_ht_total']);
        $this->assertEquals(80, $view->getVar('totaux')['montant_tva_total']);
        $this->assertEquals(480, $view->getVar('totaux')['montant_ttc_total']);
    }


    public function testIndexStatistique()
    {
        // Authentification simulée
        Mockery::mock('alias:services\AuthService')
            ->shouldReceive('checkAuthentication')
            ->once();

        // Simulation des paramètres
        Mockery::mock('alias:yasmf\HttpHelper')
            ->shouldReceive('getParam')->with('sectoriel')->andReturn(true)
            ->shouldReceive('getParam')->with('histogramme')->andReturn(true)
            ->shouldReceive('getParam')->with('date_debut')->andReturn('2024-01-01')
            ->shouldReceive('getParam')->with('date_fin')->andReturn('2024-12-31')
            ->shouldReceive('getParam')->with('filtreJour')->andReturn('mois')
            ->shouldReceive('getParam')->with('mois_filtre')->andReturn(5)
            ->shouldReceive('getParam')->with('annee_filtre')->andReturn(2024)
            ->shouldReceive('getParam')->with('comparaison')->andReturn(false);

        // Simulation des services
        $this->noteFraisService->shouldReceive('recupererStatSectorielle')
            ->once()
            ->andReturn(['data_sectorielle']);

        $this->noteFraisService->shouldReceive('recupererStatHistogramme')
            ->once()
            ->andReturn([
                'actuel' => ['histogramme_actuel'],
                'comparaison' => ['histogramme_comparaison']
            ]);

        // Appel de la méthode
        $view = $this->noteFraisController->indexStatistique();

        // Vérifications
        $this->assertEquals("views/statistique_note_frais", $view->getRelativePath());
        $this->assertEquals(['histogramme_actuel'], $view->getVar('listHistogrammeActuel'));
        $this->assertEquals(['histogramme_comparaison'], $view->getVar('listHistogrammeComparaison'));
        $this->assertEquals(['data_sectorielle'], $view->getVar('listSectoriel'));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
