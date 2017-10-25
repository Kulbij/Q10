<?php namespace App\views;

use App\Controllers\Functions;

class View
{
	public $fields = [
		'likes',
		'solds',
		'reviews'
	];

	public function run($isAjax = false)
	{
		$data = [];

		if ($isAjax) {
			$ajaxParse = new Functions;
			$data = $ajaxParse->run($_POST);

			if (isset($_POST['field']) && in_array($_POST['field'], $this->fields)) {
				@header('Content-Type: application/json');
				echo json_encode([
					'field' => $data
				]);
				return;
			}

			return ['main' => require  __DIR__ . '/ajax.php'];
		} else {
			require  __DIR__ . '/index.php';
		}
	}
}