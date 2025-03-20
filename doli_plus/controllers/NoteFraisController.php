<?php
namespace controllers;

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
        return new View("views/liste_note_frais");
    }

    public function indexListe(): View
    {
        $listeNoteFrais = [];

        // Récupération de la liste des notes de frais complète
        $listeNoteFrais = $this->noteFraisService->recupererListeComplete();

        // Attribution du résultat de la requête à la variable de la vue
        $view = new View("views/liste_note_frais");
        $view->setVar('listeNoteFrais', $listeNoteFrais);
        return $view;
    }

    public function indexStatistique(): View
    {
        $listStat = [];
        $date_debut = HttpHelper::getParam('date_debut');
        $date_fin = HttpHelper::getParam('date_fin');
        $reinitialiser = HttpHelper::getParam('reinitialiser');
//
//        var_dump($date_debut, $date_fin, $reinitialiser);
        if ($reinitialiser == 1) {
            $date_debut = null;
            $date_fin   = null;
            $listStat   = $this->noteFraisService->recupererStat($date_debut, $date_fin);
        }

        // Récupération de la liste des notes de frais complète
        $listStat = $this->noteFraisService->recupererStat($date_debut, $date_fin);

        //var_dump($listStat);
        // Attribution du résultat de la requête à la variable de la vue
        $view = new View("views/statistique_note_frais");
        $view->setVar('listStat' , $listStat);
        $view->setVar('date_debut', $date_debut);
        $view->setVar('date_fin'  , $date_fin);
        return $view;
    }
}