<?php
namespace controllers;

use PDO;
use services\UsersService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Default controller
 */
class HomeController {

    private UsersService $usersService;

    /**
     * Create a new default controller
     */
    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    /**
     * Default action
     *
     * @return View the default view displaying all users
     */
    public function index(): View {
        $status_id = (int)HttpHelper::getParam('status_id') ?: 2 ;
        $start_letter = htmlspecialchars(HttpHelper::getParam('start_letter').'%') ?: '%';
        $search_stmt = $this->usersService->findUsersByUsernameAndStatus($start_letter, $status_id) ;
        $view = new View("views/doli_plus");
        $view->setVar('search_stmt',$search_stmt);
        return $view;
    }

}


