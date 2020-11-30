<?php

namespace app\controller;

use app\interfaces\Controller;
use app\model\BaseModel;

class ToCartCheckController extends Controller {
	public function index() {
		$this->checkIfRequest();

		$data = json_decode(array_keys($_POST)[0], true);
		
		
		$search = BaseModel::getInstance()->query('items', [
			'whereFields' => [
				'id' => $data['id'],
				'color' => $data['color'],
				'size' => $data['size'],
				'count' => $data['count']
			],
			'compare' => [
				'id' => '=',
				'color' => '%LIKE%',
				'size' => '%LIKE%',
				'count' => '>='
			]
		], 'r');
		
		$search = $search[0];
		
		$search = BaseModel::getInstance()->gbpTOusd($search);
		
		$search ? $this->ajax($search) : $this->ajax(0);
	}
}
