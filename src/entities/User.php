<?php


class User
{
    protected $aUserData;

    /**
     * User constructor.
     * @param $aData
     */
    public function __construct($aData)
    {
        $this->aUserData['logged'] = false;
        if (isset($aData) && is_array($aData)) {
            foreach ($aData as $key => $value) {
                $this->aUserData[$key] = $value;
            }
            if (isset($this->aUserData['user_id']) && 0 !== $this->aUserData['user_id']) {
                $this->aUserData['logged'] = true;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->aUserData['user_id'];
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->aUserData['login'];
    }

    /**
     * @return mixed
     */
    public function getUserIsAdmin()
    {
        if (!isset($this->aUserData['admin'])) return false;
        return $this->aUserData['admin'];
    }

    public function getUserIsLogin()
    {
        return $this->aUserData['logged'];
    }

}