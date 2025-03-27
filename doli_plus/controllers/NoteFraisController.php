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
     * Crée un nouveau contrôleur de gestion des notes de frais.
     *
     * @param NoteFraisService $noteFraisService Le service de gestion des notes de frais.
     */
    public function __construct(NoteFraisService $noteFraisService)
    {
        $this->noteFraisService = $noteFraisService;
    }

    /**
     * Affiche la liste des notes de frais.
     * Vérifie que l'utilisateur est authentifié avant d'afficher la vue.
     *
     * @return View La vue des notes de frais.
     */
    public function index(): View {
        AuthService::checkAuthentication();
        return new View("views/liste_note_frais");
    }

    /**
     * Affiche la liste des notes de frais filtrées.
     * Applique les filtres récupérés via les paramètres HTTP et calcule les totaux.
     *
     * @return View La vue filtrée des notes de frais avec les totaux.
     */
    public function indexListe(): View
    {
        AuthService::checkAuthentication();

        // Récupérer la liste non triée des notes de frais
        $listeNoteFraisNonTrie = $this->noteFraisService->recupererListeComplete();

        // Récupération des paramètres de filtre
        $employe    = HttpHelper::getParam('employe', '');
        $type       = HttpHelper::getParam('type', 'TOUS');
        $reference  = HttpHelper::getParam('reference', '');
        $date_debut = HttpHelper::getParam('date_debut', '');
        $date_fin   = HttpHelper::getParam('date_fin', '');
        $etat       = HttpHelper::getParam('etat', 'tous');
        $afficherTous = HttpHelper::getParam('afficherTous', '');

        // Si on veut afficher toutes les notes
        if ($afficherTous) {
            $listeNoteFrais = $listeNoteFraisNonTrie;
            $totaux = [
                'nombre_note' => count($listeNoteFrais),
                'montant_ht_total' => array_sum(array_column($listeNoteFrais, 'montant_ht')),
                'montant_tva_total' => array_sum(array_column($listeNoteFrais, 'montant_tva')),
                'montant_ttc_total' => array_sum(array_column($listeNoteFrais, 'montant_ttc'))
            ];
        } else {
            // Appliquer les filtres et récupérer les données
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

        // Passer les données à la vue
        $view = new View("views/liste_note_frais");
        $view->setVar('listeNoteFrais', $listeNoteFrais);
        $view->setVar('totaux', $totaux);
        return $view;
    }

    /**
     * Affiche les statistiques des notes de frais (sectorielles et histogrammes).
     * Récupère les données en fonction des paramètres de filtre.
     *
     * @return View La vue des statistiques des notes de frais.
     */
    public function indexStatistique(): View
    {
        AuthService::checkAuthentication();

        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialisation des listes histogramme et sectoriel
        $listHistogramme = ['actuel' => [], 'comparaison' => []];
        $listSectoriel = [];

        // Vérification des paramètres sectorielle et histogramme
        $sectoriel   = HttpHelper::getParam('sectoriel') ?? false;
        $histogramme = HttpHelper::getParam('histogramme') ?? false;

        // Vérification de la session pour les données
        if (isset($_SESSION['listSectoriel'])) {
            $listSectoriel = $_SESSION['listSectoriel'];
        }

        if (isset($_SESSION['listHistogramme'])) {
            $listHistogramme = $_SESSION['listHistogramme'];
        }

        // Récupération des paramètres de dates et filtres
        $date_debut  = HttpHelper::getParam('date_debut');
        $date_fin    = HttpHelper::getParam('date_fin');
        $parMois     = HttpHelper::getParam('filtreJour') === 'mois' || HttpHelper::getParam('filtreJour') === null;
        $parJour     = HttpHelper::getParam('filtreJour') === 'jour';
        $moisChoisi  = HttpHelper::getParam('mois_filtre') ?? 0;
        $anneeChoisi = HttpHelper::getParam('annee_filtre') ?? date("Y");
        $comparaison = HttpHelper::getParam('comparaison') ?? false;

        // Si aucune donnée sectorielle n'est disponible ou si le paramètre 'sectoriel' est activé
        if ($sectoriel || !($histogramme)) {
            // Récupération des statistiques sectorielles
            $listSectoriel = $this->noteFraisService->recupererStatSectorielle($date_debut, $date_fin);
            // Sauvegarde dans la session
            $_SESSION['listSectoriel'] = $listSectoriel;
        }

        // Si aucune donnée histogramme n'est disponible ou si le paramètre 'histogramme' est activé
        if ($histogramme || !($sectoriel)) {
            // Récupération des statistiques pour l'histogramme
            $listHistogramme = $this->noteFraisService->recupererStatHistogramme($parMois, $parJour, $moisChoisi, $anneeChoisi,$comparaison);
            // Sauvegarde dans la session
            $_SESSION['listHistogramme'] = $listHistogramme;
        }

        // Attribution du résultat à la vue, en passant les deux listes séparément
        $view = new View("views/statistique_note_frais");
        $view->setVar('listHistogrammeActuel', $listHistogramme['actuel']);
        $view->setVar('listHistogrammeComparaison', $listHistogramme['comparaison']);
        $view->setVar('listSectoriel'  , $listSectoriel);
        $view->setVar('date_debut'     , $date_debut);
        $view->setVar('date_fin'       , $date_fin);
        $view->setVar('parMois'        , $parMois);
        $view->setVar('parJour'        , $parJour);
        $view->setVar('moisChoisi'     , $moisChoisi);
        $view->setVar('anneeChoisi'    , $anneeChoisi);
        $view->setVar('comparaison', $comparaison);

        return $view;
    }
}