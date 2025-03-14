<?php

namespace services;

/*
 * Dans le modèle yasmf, cette classe sert à intéragir avec la BD.
 * C'est la classe contenant les différentes méthodes récupérant les données, en
 * faisant des requêtes.
 * -----------------------------------------------------------------------------
 * Or, étant donnné que dans notre projet aucune intéraction avec une BD va avoir
 * lieu.
 * Cette classe va contenir toutes les requêtes (HTTP) à l'API afin de récupérer
 * les données souhaitées.
 */

class AuthService{

    /**
    * Find users by criteria
    *
    * @param string $likeUsername the string the username should contain
    * @param int $statusId the status id
    *   @return string the statement referencing the result set
    */
    public function findUsersByUsernameAndStatus(string $likeUsername, int $statusId): string
    {
        return "STUB : " . $likeUsername . " + " . $statusId;
    }

    /**
     * Vérifie si les identifiants de l'utilisateur sont valides
     *
     * @param string $username le nom d'utilisateur
     * @param string $password le mot de passe
     * @return bool vrai si l'utilisateur est authentifié, sinon faux
     */
    public function authenticate(string $username, string $password): bool
    {
        // Stub : Vérification simplifiée pour la démonstration
        // Dans un vrai projet, on comparerait ici les identifiants avec une base de données
        if ($username === "admin" && $password === "password123") {
            return true; // Authentification réussie
        }
        return false; // Authentification échouée
    }

}
