<?php

require __DIR__.'/Autoloader.php';
require __DIR__.'/simple_html_dom.php';
// require __DIR__.'/Config/App.php';

use App\Controllers\Functions;
use App\Views\View;

$view = new View();

if (isset($_GET['ajax']) && $_GET['ajax'] == 'on') {
	$view->run(true);
	exit;
}

// $user = new Functions;
// $data = $user->runParsCategories();


$view->run();