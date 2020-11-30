<?php

namespace app\controller;

use app\exceptions\RouteException;
use app\settings\Settings;
use app\interfaces\Controller;
use app\logs\SystemLogs;

class RouteController extends Controller{
    public function route() {
        $routes = Settings::getInstance()->routes;
        $page = '';
        $parameters = '';

        $url = $_SERVER['REQUEST_URI'];
        $url = substr($url, 1);
        if (strpos($url, '?')) {
            $parameters = substr($url, strpos($url, '?'));
            $url = str_replace($parameters, '', $url);
        }
        if ($url===$routes['admin']['alias']) {
            $page = $routes['admin']['routes'][$url[1]];
        } else {
            $page = $routes['user']['routes'][$url];
		}
		SystemLogs::writeSystemLog('Load url: "' . $url . '", Controller: "' . $page . '", with parameters : "' . $parameters . '"');
		if ($page=="")
			throw new RouteException('Page not found', 404);
		if (strpos($page, 'Controller')!==false) {
			$pos =  strpos($page, '#')!==false ? strpos($page, '#') : strlen($page);
			$controller = CONTROLLERS . substr($page, 0, $pos);
			$method = strpos($page, '#')!==false ? substr($page, strpos($page, '#')+1) : 'index';
			$reflectionMethod = new \ReflectionMethod($controller, $method);
			$reflectionMethod->invoke(new $controller, $parameters);
		} else {
			$this->view($page, $parameters);
		}
    }
}