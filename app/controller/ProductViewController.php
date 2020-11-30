<?php

namespace app\controller;

use app\model\BaseModel;
use app\exceptions\RouteException;
use app\interfaces\Controller;

class ProductViewController extends Controller {
	public function index($pageParameters) {
		$pageParameters = str_replace('?', '', $pageParameters);
		
		$pos = strpos($pageParameters, 'id');
		
		if ($pos===false || $pos>0) {
			throw new RouteException("Page not found", 404);
		}
		
		$pageParameters = str_replace('id=', '', $pageParameters);
		
		$id = $pageParameters;
		
		if (preg_match("/[^\d]/", $id)) {
			throw new RouteException("Page not found", 404);
		}
		
		$item = BaseModel::getInstance()->query('items', [
			'whereFields' => [
				'id' => $id
			],
			'join'=> [
				[
					'table'=>'menu_links',
					'condition'=>'menu_links.id=items.category_id',
					'fields'=>['page_name']
				],
				[
					'table'=>'submenus',
					'condition'=>'submenus.id=menu_links.menu_id',
					'fields'=>['submenu_name']
				],
				[
					'table'=>'menus',
					'condition'=>'menus.id=submenus.menu_id',
					'fields'=>['menu_name']
				]
			]
		], 'r');
		
		if (count($item)==0) {
			throw new RouteException("Page not found", 404);
		}
		
		$reviews = BaseModel::getInstance()->query('reviews', [
			'whereFields' => [
				'item' => $id
			],
			'join' => [
				[
					'table' => 'users',
					'fields' => ['login'],
					'condition' => 'reviews.user_id=users.id'
				]
			]
		], 'r');

		$item = $item[0];
		
		$category = $item['page_name'];
		$submenu = $item['submenu_name'];
		$menu = $item['menu_name'];

		unset($item['page_name']);
		unset($item['submenu_name']);
		unset($item['menu_name']);
		
		$item = BaseModel::getInstance()->gbpTOusd($item);
		
		$item['view_images'] = explode(' ', $item['view_images']);
		
		if (!$item) {
			$errMessage = 'Item not found';
			include(USER_VIEWS . 'error.view.php');
			exit();
		}
		$productError = !($item['name'] && $item['view_images'] && $item['cost_gbp'] && $menu && $submenu && $category);

		if ($productError) {
			throw new RouteException('Can\'t find selected item. Sorry for temporary inconvenience<br><a href="/home">Back to home page</a>', 1, 'item id - ' . $item['id']);
		}
		
		$this->view('product', ['item'=>$item, 'title'=> $menu . ' - ' . $submenu . ' - ' . $category . ' - <span class="intro-product-name">' . $item['name'] . '</span>', 'reviews'=> $reviews]);
	}

	public function addReview() {
		$this->checkIfRequest();
		$this->checkToken();

		$data = $_POST;
		
		$data['text'] = htmlspecialchars($data['text']);
		
		$user = BaseModel::getInstance()->query('users', [
			'whereFields' => [
				'login' => $_SESSION['login']
			]
		], 'r');
		
		$data['date'] = date('Y-m-d', time());
		$data['user_id'] = $user[0]['id'];
		
		$res = BaseModel::getInstance()->query('reviews', $data, 'c');
	}

	public function changePage() {

		$data = json_decode(array_keys($_POST)[0], true);
		
		$id = $data['id'];
		$page = $data['current_page'];
		
		$reviews = BaseModel::getInstance()->query('reviews', [
			'whereFields' => [
				'item' => $id
			],
			'join' => [
				[
					'table' => 'users',
					'fields' => [
						'login'
					],
					'condition' => 'reviews.user_id=users.id'
				]
			]
		], 'r');
		
		$result = array_splice($reviews, ($page-1)*REVIEWS_ON_PAGE, REVIEWS_ON_PAGE);
		
		$this->ajax($result);
	}
}
