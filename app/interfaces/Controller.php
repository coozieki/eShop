<?php 

namespace app\interfaces;

use app\exceptions\RouteException;

abstract class Controller extends Singletone {
	protected static $checkAuth = false;

	use BaseMethods;

	protected function __construct()
	{
		if (static::$checkAuth) {
			if (!$_SESSION['auth']) {
				$this->redirect('signin');
			}
		}
	}

	protected function checkToken() {
		if ($_POST['token'] !== $_SESSION['token']) {
			$this->ajax(3);
			exit();
		}
		unset($_POST['token']);
	}

	protected function checkIfRequest() {
		if (!$_POST) {
			throw new RouteException('Page not found', 404);
			exit();
		}
	}
}