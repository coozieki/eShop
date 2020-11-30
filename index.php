<?php
use app\model\BaseModel;


define('VG_ACCESS', true);

require_once('config.php');
require_once('app/settings/internal_settings.php');

session_start();

if (empty($_SESSION['token']))
	$_SESSION['token'] = bin2hex(random_bytes(32));

if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
    if ( !empty($_COOKIE['login']) and !empty($_COOKIE['key']) ) {
        $login = $_COOKIE['login']; 
        $key = $_COOKIE['key'];
        
        $user = BaseModel::getInstance()->query('users', [
            'email' => $login,
            'cookie' => $key
        ], 'r');

        session_start();
        if ($user) {
            $_SESSION['auth'] = true;
            $_SESSION['login'] = $user[0]['email'];
        } else {
            $_SESSION['auth'] = false;
            $_SESSION['login'] = '';
        }
    }
}


use app\exceptions\RouteException;
use app\controller\RouteController;
use app\logs\SystemLogs;

try {
	RouteController::getInstance()->route();
	SystemLogs::nextLog();
} catch (RouteException $e) {
    $errMessage = $e->errorMessage();
    include(USER_VIEWS . 'error.view.php');
}
