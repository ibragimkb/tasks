<?php

declare(strict_types = 1);

namespace TaskManager;

class App
{

    protected $controller;
    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->controller = new Controller();
    }

    public function Run()
    {
        if (isset($_GET['action']) && !empty($_GET['action'])) {
            $action = $_GET['action'];
        }
        else {
            $action = 'main';
        }

        switch ($action) {
            case 'main':
                $this->controller->{$action}($_GET);
                break;
            case 'login':
                $this->controller->{$action}();
                break;
            case 'auth':
                $this->controller->{$action}($_POST);
                break;
            case 'logout':
                $this->controller->{$action}();
                break;

            case 'addTask':
                $this->controller->{$action}();
                break;
            case 'saveTask':
                $this->controller->{$action}($_POST);
                break;

            case 'editTask':
                $this->controller->{$action}($_GET);
                break;
            case 'updateTask':
                $this->controller->{$action}($_GET, $_POST);
                break;

            default:
                $this->controller->main();
                break;
        }
    }
}