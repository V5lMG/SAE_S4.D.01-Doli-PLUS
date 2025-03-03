<?php
namespace controllers;

use yasmf\HttpHelper;
use services\UsersService;
use yasmf\View;

class ArticlesController {

    private UsersService $usersService;

    /**
     * Create a new default controller
     */
    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    public function index($pdo): View {
        try {
            $codeCategorie = HttpHelper::getParam("code_categorie");
            $designationCategorie = HttpHelper::getParam("categorie");

            $search_stmt = $this->usersService->returnAllArticlesByCategorie($pdo, $codeCategorie, $designationCategorie) ;
            $view = new View("/views/all_articles");
            $view->setVar('search_stmt',$search_stmt);
            return $view;
        } catch(PDOException $exception) {
            throw new PDOException($exception->getMessage(), (int)$exception->getCode());
        }
    }
}