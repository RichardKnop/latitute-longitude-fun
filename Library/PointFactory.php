<?php

class PointFactory
{

	/**
	 * Creates and instance of Point and returs it
	 * @param type $latitude
	 * @param type $longitude
	 * @param type $timestamp
	 * @return \Point
	 */
	public function makePoint($latitude, $longitude, $timestamp)
	{
		$point = new Point();
		$point->setLatitude($latitude);
		$point->setLongitude($longitude);
		$point->setTimestamp($timestamp);
		return $point;
	}

}
