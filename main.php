<?php

// Define BASE_PATH constant
if (!defined('BASE_PATH')) {
	define('BASE_PATH', dirname(__FILE__));
}

// Autoload classes based on classmap file
function __autoload($className)
{
	$autoloadClassmap = include BASE_PATH . '/autoload_classmap.php';
	if (FALSE === array_key_exists($className, $autoloadClassmap)) {
		throw new Exception('Could not autoload ' . $className);
		
	}
	require_once BASE_PATH . '/' . $autoloadClassmap[$className];
}

$route = new Route(BASE_PATH . '/points.csv');
$route->parseCsv();
$route->sortPointsByTimestamp();

var_dump($route->getPoints());
exit;