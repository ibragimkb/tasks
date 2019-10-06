<?php


$sDirRoot = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR;

require_once($sDirRoot.'vendor/smarty/smarty/libs/Smarty.class.php');
require_once($sDirRoot.'src/entities/Task.php');
require_once($sDirRoot.'src/entities/User.php');
require_once($sDirRoot.'src/model/TaskModel.php');
require_once($sDirRoot.'src/model/UserModel.php');
require_once($sDirRoot.'src/View.php');
require_once($sDirRoot.'src/Controller.php');
require_once($sDirRoot.'src/App.php');

header("Content-Type:text/html;charset=UTF-8");

$app = new App();
$app->Run();
