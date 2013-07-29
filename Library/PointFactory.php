<?php

class PointFactory
{

	public function makePoint($latitude, $longitude, $timestamp)
	{
		$point = new Point();
		$point->setLatitude($latitude);
		$point->setLongitude($longitude);
		$point->setTimestamp($timestamp);
		return $point;
	}

}
