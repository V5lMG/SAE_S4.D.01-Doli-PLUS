<?php
namespace controllers;

use services\AuthService;
use services\NoteFraisService;
use yasmf\HttpHelper;
use yasmf\View;

class NoteFraisController
{
    private NoteFraisService $noteFraisService;

    /**
     * Create a new default controller
     */
    public function __construct(NoteFraisService $noteFraisService)
    {
        $this->noteFraisService = $noteFraisService;
    }

    /**
     * Default action
     *
     * @return View the default view displaying all users
     */
    public function index(): View {
        AuthService::checkAuthentication();
        return new View("views/liste_note_frais");
    }

    public function indexListe(): View
    {
        AuthService::checkAuthentication();

        $listeNoteFraisNonTrie = $this->noteFraisService->recupererListeComplete();

        $employe    = HttpHelper::getParam('employe', '');
        $type       = HttpHelper::getParam('type', 'TOUS');
        $reference  = HttpHelper::getParam('reference', '');
        $date_debut = HttpHelper::getParam('date_debut', '');
        $date_fin   = HttpHelper::getParam('date_fin', '');
        $etat       = HttpHelper::getParam('etat', 'tous');
        $afficherTous = HttpHelper::getParam('afficherTous', '');

        if ($afficherTous) {
            $listeNoteFrais = $listeNoteFraisNonTrie;
            $totaux = [
                'nombre_note' => count($listeNoteFrais),
                'montant_ht_total' => array_sum(array_column($listeNoteFrais, 'montant_ht')),
                'montant_tva_total' => array_sum(array_column($listeNoteFrais, 'montant_tva')),
                'montant_ttc_total' => array_sum(array_column($listeNoteFrais, 'montant_ttc'))
            ];
        } else {
            $filteredData = $this->noteFraisService->filtrerValeurs(
                $listeNoteFraisNonTrie,
                $employe,
                $type,
                $reference,
                $date_debut,
                $date_fin,
                $etat
            );
            $listeNoteFrais = $filteredData['notes'];
            $totaux = $filteredData['totaux'];
        }

        $view = new View("views/liste_note_frais");
        $view->setVar('listeNoteFrais', $listeNoteFrais);
        $view->setVar('totaux', $totaux);
        return $view;
    }

    public function indexStatistique(): View
    {
        AuthService::checkAuthentication();

        // Initialisation des listes histogramme et sectoriel
        $listHistogramme = [];
        $listSectoriel = [];

        // Vérification des paramètres sectoriel et histogramme
        $sectoriel   = HttpHelper::getParam('sectoriel') ?? false;
        $histogramme = HttpHelper::getParam('histogramme') ?? false;

        // Récupération des paramètres de dates et filtres
        $date_debut = HttpHelper::getParam('date_debut');
        $date_fin = HttpHelper::getParam('date_fin');
        $parMois = HttpHelper::getParam('filtreJour') === 'mois' || HttpHelper::getParam('filtreJour') === null;
        $parJour = HttpHelper::getParam('filtreJour') === 'jour';
        $moisChoisi = HttpHelper::getParam('mois_filtre') ?? 0;
        $anneeChoisi = HttpHelper::getParam('annee_filtre') ?? date("Y");

        // Si aucune donnée sectorielle n'est disponible ou si le paramètre 'sectoriel' est activé
        if ($sectoriel || !($sectoriel || $histogramme)) {
            // Récupération des statistiques sectorielles
            $listSectoriel = $this->noteFraisService->recupererStatSectorielle($date_debut, $date_fin);
        }

        // Si aucune donnée histogramme n'est disponible ou si le paramètre 'histogramme' est activé
        if ($histogramme || !($sectoriel || $histogramme)) {
            // Récupération des statistiques pour l'histogramme
            $listHistogramme = $this->noteFraisService->recupererStatHistogramme($parMois, $parJour, $moisChoisi, $anneeChoisi);
        }

        // Attribution du résultat à la vue, en passant les deux listes séparément
        $view = new View("views/statistique_note_frais");
        $view->setVar('listHistogramme', $listHistogramme);
        $view->setVar('listSectoriel'  , $listSectoriel);
        $view->setVar('date_debut'     , $date_debut);
        $view->setVar('date_fin'       , $date_fin);
        $view->setVar('parMois'        , $parMois);
        $view->setVar('parJour'        , $parJour);
        $view->setVar('moisChoisi'     , $moisChoisi);

        return $view;
    }
}