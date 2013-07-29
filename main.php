<?php

// Define BASE_PATH constant
if (!defined('BASE_PATH')) {
	define('BASE_PATH', dirname(__FILE__));
}
require_once BASE_PATH . '/autoload_classmap.php';

$route = new Route(BASE_PATH . '/points.csv');
$route->parseCsv();
$route->sortPointsByTimestamp();
$buffer = 0.5;
$route->removeDistantPoints($buffer);

echo print_r($route->getPoints(), true);
exit;