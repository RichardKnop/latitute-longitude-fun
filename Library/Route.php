<?php

class Route
{

	private $csvPath;
	private $points = array();

	/**
	 * Constructor, assings CSV path to $this->csvPath property
	 * @param type $csvPath
	 */
	public function __construct($csvPath)
	{
		$this->csvPath = $csvPath;
	}

	/**
	 * Parses the CSV file and populates $this->points
	 * @throws FileNotFoundException
	 * @return void
	 */
	public function parseCsv()
	{
		$md5s = array(); // used to remove duplicates
		// Throw an exception if we cannot open a CSV file handle
		if (FALSE === ($handle = fopen($this->csvPath, 'r'))) {
			throw new FileNotFoundException('Could not open CSV handle');
		}

		// Factory to create Point objects
		$pointFactory = new PointFactory();

		// Iterate over CSV and populate $this->points
		while (FALSE !== ($data = fgetcsv($handle, 1000, ','))) {

			/*
			 * Compute an MD5 hash of latitude / longitute 
			 * combination, ignore duplicate points
			 */
			list($latitude, $longitude, $timestamp) = $data;
			$md5 = md5($latitude, $longitude);
			if (FALSE === in_array($md5, $md5s)) {
				// Store the MD5 to avoid future duplicates
				array_push($md5s, $md5);

				// Create instance of Point and set its properties
				$point = $pointFactory->makePoint($latitude, $longitude, $timestamp);

				// Push the valid point to the array
				array_push($this->points, $point);
			}
		}

		fclose($handle);
	}

	/**
	 * Sorst $this->points based on timestamp property
	 * @return void
	 */
	public function sortPointsByTimestamp()
	{
		usort(
			$this->points, function($a, $b) {
				return $a->getTimestamp() > $b->getTimestamp() ? 1 : -1;
			}
		);
	}

	/**
	 * http://en.wikipedia.org/wiki/Haversine_formula
	 * Calculate the distance between A and B (beginning and end of journey), 
	 * then iterate over all points and remove those whose distance is larger.
	 * @return void
	 */
	public function removeDistantPoints($buffer)
	{
		$haversine = new HaversineFormula();

		// Calculate the distance between A and B (beginning and end of journey)
		$firstPoint = $this->points[0];
		$lastPoint = $this->points[count($this->points) - 1];
		$maxDistance = $haversine->calculateDistance($firstPoint, $lastPoint) + $buffer;

		$filteredPoints = array();
		array_push($filteredPoints, $firstPoint);

		// Iterate over all points except A and B and filter out those points, 
		// whose distance from either A or B is greater than A-B distance
		for ($i = 1; $i < count($this->points) - 1; ++$i) {
			$point = $this->points[$i];
			$distanceA = $haversine->calculateDistance($firstPoint, $point);
			$distanceB = $haversine->calculateDistance($lastPoint, $point);
			if ($distanceA < $maxDistance && $distanceB < $maxDistance) {
				array_push($filteredPoints, $point);
			}
		}

		array_push($filteredPoints, $lastPoint);
		$this->points = $filteredPoints;
	}

	/**
	 * Gets points
	 * @return array
	 */
	public function getPoints()
	{
		return $this->points;
	}

}
