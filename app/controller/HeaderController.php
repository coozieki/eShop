<?php

namespace app\controller;

use app\interfaces\Controller;
use app\logs\SystemLogs;
use app\model\BaseModel;

class HeaderController extends Controller {
	public function loadMenu() {
		$data = $_POST;
		
		$menuVersion =  BaseModel::getInstance()->query('versions', [], 'r')[0]['menu_version'];
		
		if (isset($_COOKIE['menu'])) {
			if (substr($_COOKIE['menu'], 0, strlen($menuVersion))==$menuVersion) {
				echo substr($_COOKIE['menu'], strlen($menuVersion));
				SystemLogs::nextLog();
				exit();
			}
		}
		
		$menus = BaseModel::getInstance()->query('menus',[], 'r');
		
		$submenus = BaseModel::getInstance()->query('submenus', [], 'r');
		
		$pages = BaseModel::getInstance()->query('menu_links', [], 'r');
		
		$response = [];
		
		$response['menus'] = $menus;
		$response['submenus'] = $submenus;
		$response['pages'] = $pages;
		
		setcookie('menu', $menuVersion . $response, time()+60*60*24*30);
		
		SystemLogs::nextLog();
		
		$this->ajax($response);
	}

	public function search() {
		$data = $_POST;
		
		$data['search'] = trim($data['search']);
		
		$searchRes = BaseModel::getInstance()->query('items', [
			'whereFields' => [
				'search_tags' => $data['search'],
				'name' => $data['search'],
			],
			'compare' => [
				'search_tags' => '%LIKE%',
				'name' => '%LIKE%'
			],
			'operands' => [
				'name' => 'OR'
			]
		], 'r');

		$this->ajax($searchRes);
	}
}