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
     * Find users by criteria
     *
     * @param string $likeUsername the string the username should contain
     * @param int $statusId the status id
     * @return string the statement referencing the result set
     */
    public function findUsersByUsernameAndStatus(string $likeUsername, int $statusId): string
    {
        return "STUB : " . $likeUsername . " + " . $statusId;
    }

}