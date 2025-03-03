<?php


namespace services;


use PDO;
use PDOStatement;


/**
 * The users service class
 */
class UsersService
{
    /**
     * Renvoyer toutes les catégories
     * 
     * @param PDO $pdo the pdo object
     * @return PDOStatement the statement referencing the result set
     */
    public function returnAllCategories(PDO $pdo): PDOStatement
    {
        $sql = "select code_categorie, designation 
               from a_categories
               order by code_categorie";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute();
        return $searchStmt;
    }

    /**
     * Renvoyer toutes les articles
     * 
     * @param PDO $pdo the pdo object
     * @return PDOStatement the statement referencing the result set
     */
    public function returnAllArticlesByCategorie(PDO $pdo, $codeCategorie): PDOStatement
    {
        $sql = "select id_article, code_article, designation 
                from articles
                where categorie = ?
                order by code_article";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute([$codeCategorie]);
        return $searchStmt;
    }
}