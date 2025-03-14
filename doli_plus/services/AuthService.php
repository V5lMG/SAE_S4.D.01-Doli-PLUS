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

}
