<?php

namespace app\controller;

use app\interfaces\Controller;
use app\model\BaseModel;

class LoadItemsController extends Controller {
	public function index($params) {
		$data = $_POST;
		
		$res = BaseModel::getInstance()->query('shop_items_home', [
			'whereFields' => [
				'slideNumber' => $_POST['slideNumber']
			],
			'fields'=> [
				'item_id'
			],
			'join' => [
				[
					'table' => 'items',
					'fields' => [
						'cost_gbp',
						'sale_gbp',
						'description',
						'name',
						'id',
						'image_url'
					],
					'condition'=>'items.id=shop_items_home.item_id'
				]
			]
		], 'r');
		
		foreach($res as $key => $value)
		{
			$res[$key] = BaseModel::getInstance()->gbpTOusd($res[$key]);
		}

		$this->ajax($res);
	}
}
