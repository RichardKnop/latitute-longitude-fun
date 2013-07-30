<?php

class HaversineFilterStrategy implements FilterStrategyInterface
{
	
	/**
	 * 
	 * @param array $points
	 * @param type $buffer
	 * @return array
	 */
	public function filterPoints(array &$points, $buffer)
	{
		$haversine = new HaversineFormula();

		// Calculate the distance between A and B (beginning and end of journey)
		$firstPoint = $points[0];
		$lastPoint = $points[count($points) - 1];
		$maxDistance = $haversine->calculateDistance($firstPoint, $lastPoint) + $buffer;

		$filteredPoints = array();
		array_push($filteredPoints, $firstPoint);

		// Iterate over all points except A and B and filter out those points, 
		// whose distance from either A or B is greater than A-B distance
		for ($i = 1; $i < count($points) - 1; ++$i) {
			$point = $points[$i];
			$distanceA = $haversine->calculateDistance($firstPoint, $point);
			$distanceB = $haversine->calculateDistance($lastPoint, $point);
			if ($distanceA < $maxDistance && $distanceB < $maxDistance) {
				array_push($filteredPoints, $point);
			}
		}

		array_push($filteredPoints, $lastPoint);

		return $filteredPoints;
	}

}