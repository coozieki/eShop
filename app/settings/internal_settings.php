<?php

defined('VG_ACCESS') or die('Access denied');

use app\exceptions\RouteException;

function autoloadMainClasses($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    if (!@include_once  $class_name . '.php') {
        throw new RouteException('Неверное имя файла для подключения - ' .$class_name);
    }
}

spl_autoload_register('autoloadMainClasses');