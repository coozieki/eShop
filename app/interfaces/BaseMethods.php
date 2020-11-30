<?php 

namespace app\interfaces;

trait BaseMethods {
	protected function view($page, $params=[]) {
		include(USER_VIEWS . $page . '.view.php');
		exit();
	}

	protected function ajax($data) {
		echo json_encode($data);
		return;
	}

	protected function redirect($page) {
		header('Location: ' . $page);
		exit();
	}
}