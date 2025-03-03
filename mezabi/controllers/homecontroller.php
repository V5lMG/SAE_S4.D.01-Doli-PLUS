<?php
namespace controllers;

use yasmf\View;
use services\UsersService;

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
     * @param PDO $pdo  the PDO object to connect to the database
     * @return View the default view displaying all users
     */
    public function index($pdo): View {
        $search_stmt = $this->usersService->returnAllCategories($pdo) ;
        $view = new View("views/all_categories");
        $view->setVar('search_stmt',$search_stmt);
        return $view;
    }

}
