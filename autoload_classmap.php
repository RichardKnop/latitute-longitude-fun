<?php

$classmap = array(
	'Point' => 'Library/Point.php',
	'PointFactory' => 'Library/PointFactory.php',
	'Route' => 'Library/Route.php',
	'FileNotFoundException' => 'Library/Exception/FileNotFound.php',
	'HaversineFormula' => 'Library/HaversineFormula.php',
	'FilterStrategyInterface' => 'Library/FilterStrategy/Interface.php',
	'FilterStrategyContext' => 'Library/FilterStrategy/Context.php',
	'HaversineFilterStrategy' => 'Library/FilterStrategy/Haversine.php',
	'InvalidFilterStrategyException' => 'Library/Exception/InvalidFilterStrategy.php'
);
foreach ($classmap as $file) {
	require_once BASE_PATH . '/' . $file;
}