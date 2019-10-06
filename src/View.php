<?php


use Smarty;

class View
{

    protected  $smarty;
    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->smarty = new Smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/templates/');
        $this->smarty->setCompileDir(__DIR__ . '/tmp/');
        $this->smarty->debugging = false;
        $this->smarty->caching = false;

    }

    public function getMainPage(array &$aTasks, array &$aPages, $iPage, User $oUser)
    {
        $this->smarty->assign("title", "Main");
        $this->smarty->assign("aTasks", $aTasks);
        $this->smarty->assign("aPages", $aPages);
        $this->smarty->assign("oUser", $oUser);
        $this->smarty->assign("iPage", $iPage);
        $this->smarty->display('index.tpl');
    }

    public function getAddTaskPage()
    {
        $this->smarty->assign("title", "Add task");
        $this->smarty->display('addtask.tpl');
    }

    public function getEditTaskPage(Task &$oTask)
    {
        $this->smarty->assign("title", "Edit task");
        $this->smarty->assign("oTask", $oTask);
        $this->smarty->display('edittask.tpl');
    }

    public function getLoginPage()
    {
        $this->smarty->assign("title", "Login");
        $this->smarty->display('login.tpl');
    }

    public function getSuccessFormPage()
    {
        $this->smarty->assign("title", "Login");
        $this->smarty->display('success.tpl');
    }

    public function getErrorLoginPage()
    {
        $this->smarty->assign("title", "Error login");
        $this->smarty->display('errorlogin.tpl');
    }

    public function getErrorFormPage(array &$aError)
    {
        $this->smarty->assign("title", "Error");
        $this->smarty->assign("aError", $aError);
        $this->smarty->display('errorform.tpl');
    }

}