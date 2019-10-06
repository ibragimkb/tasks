<?php

declare(strict_types = 1);

namespace TaskManager;

use PDO;

class TaskModel
{

    protected $connection;
    /**
     * TaskModel constructor.
     */
    public function __construct(PDO &$connection)
    {
        $this->connection = $connection;
    }

    public function getTasksCount()
    {
        $sql = "SELECT COUNT(task_id) AS tasks_count FROM tasks";

        $st = $this->connection->prepare($sql);
        $st->execute();

        $st->setFetchMode(PDO::FETCH_ASSOC);
        $result = $st->fetchAll();

        return $result[0]['tasks_count'];
    }

    public function getTasks(int $limit, int $offset = 0, string $field = 'task_id', string $order = 'ASC'): array
    {
        $sql = 'SELECT task_id, user_name, email, task_text, task_done, task_edited 
                  FROM tasks 
                  ORDER BY ' . $field . ' ' . $order . ' 
                  LIMIT :offset , :limit';

        $st = $this->connection->prepare($sql);
        $st->bindParam(':offset', $offset, PDO::PARAM_INT);
        $st->bindParam(':limit', $limit, PDO::PARAM_INT);

        $st->execute();

        $st->setFetchMode(PDO::FETCH_ASSOC);
        $result = $st->fetchAll();
        $tasks = [];

        foreach ($result as $k => $task) {
            $tasks[$k] = new Task($task);
        }
        return $tasks;
    }

    public function getTask(int $Id) : Task
    {
        $sql = 'SELECT task_id, user_name, email, task_text, task_done, task_edited 
                  FROM tasks WHERE task_id = :task_id';

        $st = $this->connection->prepare($sql);
        $st->bindParam(':task_id', $Id);
        $st->execute();

        $st->setFetchMode(PDO::FETCH_ASSOC);
        $result = $st->fetchAll()[0];

        return (new Task($result));
    }

    public function addTask(Task $oTask) : bool
    {
        $st = $this->connection->prepare('INSERT INTO tasks (user_name, email, task_text) VALUES (:user_name, :email, :task_text)');
        $p1 = $oTask->getUserName();
        $p2 = $oTask->getEmail();
        $p3 = $oTask->getTaskText();
        $st->bindParam(':user_name', $p1);
        $st->bindParam(':email', $p2);
        $st->bindParam(':task_text', $p3);

        return $st->execute();
    }

    public function updateTask(Task $oTask)
    {
        $st = $this->connection->prepare(
            'UPDATE tasks 
                          SET task_text = :task_text, task_done = :task_done, task_edited = :task_edited 
                            WHERE task_id = :task_id'
        );

        $p1 = $oTask->getTaskText();
        $p2 = $oTask->getTaskDoneMark();
        $p3 = $oTask->getTaskEditedMark();
        $p4 = $oTask->getTaskId();
        $st->bindParam(':task_text', $p1);
        $st->bindParam(':task_done', $p2, PDO::PARAM_BOOL);
        $st->bindParam(':task_edited', $p3, PDO::PARAM_BOOL);
        $st->bindParam(':task_id', $p4, PDO::PARAM_INT);
        return $st->execute();
    }

    public function doneTask(Task $oTask)
    {
        $st = $this->connection->prepare('UPDATE tasks SET task_done = :task_done WHERE task_id = :task_id');
        $st->bindParam(':task_done', $oTask->getTaskDoneMark());
        $st->bindParam(':task_id', $oTask->getTaskId());
        return $st->execute();
    }

}