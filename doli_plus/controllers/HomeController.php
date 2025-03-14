<?php
namespace controllers;

use services\AuthService;
use yasmf\HttpHelper;
use yasmf\View;

class HomeController
{
    private AuthService $authService;

    /**
     * Create a new default controller
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Default action
     *
     * @return View the default view displaying all users
     */
    public function index(): View {
        $status_id = (int)HttpHelper::getParam('status_id') ?: 2 ;
        $start_letter = htmlspecialchars(HttpHelper::getParam('start_letter').'%') ?: '%';
        $search_stmt = $this->authService->findUsersByUsernameAndStatus($start_letter, $status_id) ;
        $view = new View("views/doli_plus");
        $view->setVar('search_stmt',$search_stmt);
        return $view;
    }
}
