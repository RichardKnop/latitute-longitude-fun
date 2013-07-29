<?php

class HaversineFormula
{

	/**
	 * http://en.wikipedia.org/wiki/Haversine_formula
	 * Calculates a distance between two points
	 * @param Point $a
	 * @param Point $b
	 * @return float
	 */
	public function calculateDistance(Point $a, Point $b)
	{
		$R = 6371; // km
		$dLat = deg2rad(abs($b->getLatitude() - $a->getLatitude()));
		$dLon = deg2rad(abs($b->getLongitude() - $a->getLongitude()));
		$lat1 = deg2rad($a->getLatitude());
		$lat2 = deg2rad($b->getLatitude());

		$a = sin($dLat / 2) * sin($dLat / 2)
			+ sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $R * $c;
	}

}