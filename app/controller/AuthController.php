<?php

namespace app\controller;

use app\exceptions\RouteException;
use app\interfaces\Controller;
use app\model\BaseModel;

class AuthController extends Controller {
	public function login($params) {
		$this->checkIfRequest();
		$this->checkToken();

		$data = $_POST;
		$result = 1;
		
		try {
			$res = BaseModel::getInstance()->query('users', [
				'whereFields' => [
					'login' => $data['login'],
					'password' => $data['password']
				],
			], 'r');
			if ($res == false)
				$result = 2;
		}
		catch (RouteException $e) {
			$result = false;
			$e->errorMessage();
		}
		
		if ($result == 1) {
			BaseModel::getInstance()->updateCookie($data);
		}

		$this->ajax($result);
	}

	public function register() {
		$this->checkIfRequest();
		$this->checkToken();
		$data = $_POST;
		unset($data['conf-password']);
		unset($data['g-recaptcha-response']);
		$result = 1;
		try {
			if (!BaseModel::getInstance()->query('users', [
				'whereFields' => [
					'email' => $data['email']
				],
			], 'r'))
				$result = BaseModel::getInstance()->query('users', $data, 'c');
			else
				$result = 2;
		}
		catch (RouteException $e) {
			$result = false;
			$e->errorMessage();
		}
		
		if ($result==1) {
			BaseModel::getInstance()->updateCookie($data);
		}
			
		$this->ajax($result);
	}

	public function logout() {
		setcookie('login', '', time()-3600);
		setcookie('key', '', time()-3600);
		$_SESSION['auth'] = false;
		unset($_SESSION['login']);
		$current = $_SERVER;
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
}
