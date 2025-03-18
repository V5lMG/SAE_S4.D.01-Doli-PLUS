<?php
namespace controllers;

use services\NoteFraisService;
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
        $listNoteFrais = [];

        // Récupération de la liste des notes de frais complète
        $listNoteFrais = $this->noteFraisService->recupererListeComplete();

        // Attribution du résultat de la requête à la variable de la vue
        $view = new View("views/liste_note_frais");
        $view->setVar('noteFrais', $listNoteFrais);
        return $view;
    }

    public function statistique(): View
    {
        return new View("views/statistique_note_frais");
    }
}
