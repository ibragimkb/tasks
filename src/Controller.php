<?php

declare(strict_types = 1);

namespace TaskManager;

use PDO;

class Controller
{
    const TASK_PER_PAGE = 3;
    const ORDER_FIELDS = ['task_id', 'user_name', 'email', 'task_done'];
    protected $tasks;
    protected $view;
    protected $user;
    protected $connection;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=localhost;dbname=tasks;charset=utf8", '***', '***');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }

        session_start();
        $this->tasks = new TaskModel($this->connection);
        $this->user = new UserModel($this->connection);
        $this->view = new View();
    }


    public function main(array $aGet = [])
    {
        $page = 1;
        $order = 'ASC';
        $field = 'task_id';

        if (isset($_SESSION['order'])) {
            $order = $_SESSION['order'];
        }
        if (isset($_SESSION['field'])) {
            $field = $_SESSION['field'];
        }

        if (!empty($aGet)) {
            if (isset($aGet['page']) && is_numeric($aGet['page'])) {
                $page = intval($aGet['page']);
            }
            if (isset($aGet['order']) && is_numeric($aGet['order'])
                && $aGet['order'] < count($this::ORDER_FIELDS) ) {
                //$order = $aGet['order']; //ASC и DESC

                if ('ASC' == $order) $order = 'DESC';
                else $order = 'ASC';

                $field = $this::ORDER_FIELDS[intval($aGet['order'])];

                $_SESSION['order'] = $order;
                $_SESSION['field'] = $field;
                session_write_close();
            }
            else $_SESSION['order'] = $order;
        }

        if (0 == $page) $page = 1;

        $c = $this->tasks->getTasksCount();
        $pages = round($c / $this::TASK_PER_PAGE + 0.4);
        $tasks = $this->tasks->getTasks($this::TASK_PER_PAGE, ($page - 1)* $this::TASK_PER_PAGE, $field, $order);

        for ($i=1; $i<=$pages; $i++) $p[$i] = $i;
        /*if (6 > $pages) {
            for ($i=1; $i<=$pages; $i++) $p[$i] = $i;
        }
        else {
            if ($page > 2) {

                $p[0] = 1;
                $p[1] = $page - 1;
                $p[2] = $page;
                $p[3] = $page + 1;
                $p[4] = $pages + 2;
                $p[5] = $pages;
            }
            elseif ($page == 1)  {
                $p[0] = 1;
                $p[1] = $page + 1;
                $p[2] = $page + 2;
                $p[3] = $page + 3;
                $p[4] = $page + 4;
                $p[5] = $pages;
            }
            elseif ($page == 2)  {
                $p[0] = 1;
                $p[1] = $page;
                $p[2] = $page + 1;
                $p[3] = $page + 2;
                $p[4] = $page + 3;
                $p[5] = $pages;
            }

        }*/

        $this->view->getMainPage($tasks, $p, $page, $this->user->getSessionUser());
    }

    public function login()
    {
        if ($this->userIsValid()) {
            $this->main();
        }
        else {
            $this->view->getLoginPage();
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        session_write_close();
        $this->main();
    }

    public function auth(array $aFormData)
    {
        if ($this->validateAuthData($aFormData)) {
            $user = $this->user->getUser($aFormData['login'], $aFormData['password']);

            if ($user->getUserIsLogin()) {
                $_SESSION['user'] = $user;
                session_write_close();
                $this->main();
            }
            else {
                $this->view->getErrorLoginPage();
            }
        }
        else $this->main();
    }

    public function addTask()
    {
        $this->view->getAddTaskPage();
    }

    public function saveTask(array $aFormData)
    {
        $error = [];
        if ($this->validateFormData($aFormData, $error)) {
            $task = new Task($aFormData);
            $this->tasks->addTask($task);
            //$this->main();
            $this->view->getSuccessFormPage();
        }
        else {
            $this->view->getErrorFormPage($error);
        }
    }


    public function editTask(array $aGet = [])
    {
        $Id = $this->getTaskIdFromRequest($aGet);
        if ($Id && $this->userIsValid()) {
            if (($tasks = $this->tasks->getTask($Id))){
                $this->view->getEditTaskPage($tasks);
            }
            else {
                $this->main();
            }
        }
        else {
            $this->login();
        }
    }

    public function updateTask(array $aGet, array $aFormData)
    {
        $Id = $this->getTaskIdFromRequest($aGet);
        if ($Id && $this->userIsValid()) {
            $error = [];
            if ($this->validateFormData($aFormData,$error)) {
                $task = $this->tasks->getTask($Id);

                $taskNew = new Task($aFormData);
                $taskNew->setTaskId($Id);
                $taskNew->setTaskDoneMark($aFormData['task_done']);
                if (!$task->getTaskEditedMark()) {
                    if ($task->getTaskText() != $taskNew->getTaskText()) $taskNew->setTaskEditedMark(TRUE);
                    else $taskNew->setTaskEditedMark(FALSE);
                }
                else {
                    $taskNew->setTaskEditedMark($task->getTaskEditedMark());
                }

                if ($this->tasks->updateTask($taskNew)) {
                    $this->main();
                }
                else {
                    array_push($error, 'internal error');
                    $this->view->getErrorFormPage($error);
                }
            }
            else {
                $this->view->getErrorFormPage($error);
            }
        }
        else {
            $this->login();
        }
    }


    protected function getTaskIdFromRequest(array  &$aGet) : int
    {
        if (isset($aGet['task']) && is_numeric($aGet['task'])) {
            return intval($aGet['task']);
        }
        return 0;
    }

    protected function validateFormData(array &$aFormData, array &$error) : bool
    {
        if (!isset($aFormData['user_name'])
            || !isset($aFormData['email'])
            || !isset($aFormData['task_text'])) return false;
        $c = true;

        if (empty($aFormData['user_name'])) {
            $c = false;
            array_push($error, "User name is empty");
        }
        if (empty($aFormData['email'])) {
            $c = false;
            array_push($error, "Email is empty");
        }
        if (empty($aFormData['task_text'])) {
            $c = false;
            array_push($error, "Task is empty");
        }

        if (!$c) return false;

        if (!filter_var($aFormData['email'], FILTER_VALIDATE_EMAIL)) {
            array_push($error, "Incorrect email");
            return false;
        }

        $aFormData['user_name'] = trim(htmlentities($aFormData['user_name']));
        $aFormData['email'] = trim($aFormData['email']);
        $aFormData['task_text'] = trim(htmlentities($aFormData['task_text']));

        if (!isset($aFormData['task_done'])) $aFormData['task_done'] = 0;
        if (!is_numeric($aFormData['task_done'])) $aFormData['task_done'] = 0;
        $aFormData['task_done'] = $aFormData['task_done'] ? true : false;

        return true;
    }

    protected function validateAuthData(array &$aFormData) : bool
    {
        if (!isset($aFormData['login']) || !isset($aFormData['password'])) return false;

        $aFormData['login'] = trim(strip_tags($aFormData['login']));
        $aFormData['password'] = hash('sha256', $aFormData['password']);

        if (empty($aFormData['login']) || empty($aFormData['password'])) return false;

        return true;
    }

    public function userIsValid() : bool
    {
        if ($this->user->getSessionUser()->getUserIsLogin()
            && $this->user->getSessionUser()->getUserIsAdmin()
        ) return true;
        return false;
    }

}