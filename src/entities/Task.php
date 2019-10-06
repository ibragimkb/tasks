<?php

declare(strict_types = 1);

namespace TaskManager;

class Task
{
    protected $aTaskData;

    /**
     * Task constructor.
     * @param $UserName
     */
    public function __construct($aData)
    {
        if (is_array($aData)) {
            foreach ($aData as $key => $value) {
                $this->aTaskData[$key] = $value;
            }
        }
    }

    public function getTaskId()
    {
        return $this->aTaskData['task_id'];
    }

    public function getUserName()
    {
        return $this->aTaskData['user_name'];
    }

    public function getEmail()
    {
        return $this->aTaskData['email'];
    }

    public function getTaskText()
    {
        return $this->aTaskData['task_text'];
    }

    public function getTaskDoneMark()
    {
        return $this->aTaskData['task_done'];
    }

    public function getTaskEditedMark()
    {
        return $this->aTaskData['task_edited'];
    }

    public function setTaskId(int $value)
    {
        $this->aTaskData['task_id'] = $value;
    }

    /**
     * @param string $value
     */
    public function setUserName(string $value)
    {
        $this->aTaskData['user_name'] = $value;
    }

    public function setEmail(string $value)
    {
        $this->aTaskData['email'] = $value;
    }

    public function setTaskText(string $value)
    {
        $this->aTaskData['task_text'] = $value;
    }

    public function setTaskDoneMark(bool $value)
    {
        $this->aTaskData['task_done'] = $value;
    }

    public function setTaskEditedMark(bool $value)
    {
        $this->aTaskData['task_edited'] = $value;
    }

}