<?php

declare(strict_types = 1);

namespace TaskManager;

use PDO;

class UserModel
{
    protected $connection;

    /**
     * UserModel constructor.
     * @param $connection
     */
    public function __construct(PDO &$connection)
    {
        $this->connection = $connection;
    }

    public function getUser(string $login, string $password) : User
    {
        $st = $this->connection->prepare('SELECT user_id, admin FROM users WHERE login = :login AND password = :password');
        $st->bindParam(':login', $login);
        $st->bindParam(':password', $password);

        $st->execute();

        $st->setFetchMode(PDO::FETCH_ASSOC);
        $result = $st->fetchAll();
        if ($result) return (new User($result[0]));
        return (new User([]));
    }


    public function getSessionUser() : User
    {
        if (isset($_SESSION['user']) && $_SESSION['user']->getUserIsLogin()) {
            return $_SESSION['user'];
        }
        else return (new User([]));
    }

}