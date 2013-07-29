<?php

// Define BASE_PATH constant
if (!defined('BASE_PATH')) {
	define('BASE_PATH', dirname(__FILE__));
}
require_once BASE_PATH . '/autoload_classmap.php';

try {
	$route = new Route(BASE_PATH . '/points.csv');
	$route->parseCsv();
	$route->sortPointsByTimestamp();
	$buffer = 0.5;
	$route->removeDistantPoints($buffer);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}

echo print_r($route->getPoints(), true);