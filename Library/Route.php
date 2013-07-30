<?php

class Route
{

	private $csvPath;
	private $pointMD5s = array(); // used to remove duplicates
	private $points = array();

	/**
	 * Constructor, assings CSV path to $this->csvPath property
	 * @param type $csvPath
	 */
	public function __construct($csvPath)
	{
		if (FALSE === file_exists($csvPath)) {
			throw new FileNotFoundException('CSV file not found');
		}

		$this->csvPath = $csvPath;
	}

	/**
	 * Parses the CSV file and populates $this->points
	 * @throws FileNotFoundException
	 * @return void
	 */
	public function parseCsv()
	{
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
			// Create instance of Point and set its properties
			$point = $pointFactory->makePoint($latitude, $longitude, $timestamp);
			if (FALSE === $this->isDuplicate($point)) {
				// Push the valid point to the array
				$this->addPoint($point);
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
		$firstPoint = $this->getFirstPoint();
		$lastPoint = $this->getLastPoint();
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

	private function addPoint(Point $point)
	{
		array_push($this->points, $point);
	}

	private function isDuplicate(Point $point)
	{
		$md5 = md5($point->getLatitude(), $point->getLongitude());
		$isDuplicate = in_array($md5, $this->pointMD5s);
		if (FALSE === $isDuplicate) {
			// Store the MD5 to avoid future duplicates
			array_push($this->pointMD5s, $md5);
		}
		return $isDuplicate;
	}

	/**
	 * Returns number of points in the route
	 * @return integer
	 */
	private function getPointCount()
	{
		return count($this->points);
	}

	/**
	 * Get the starting point of the journey
	 * @return \Point
	 */
	private function getFirstPoint()
	{
		return $this->points[0];
	}

	/**
	 * Get the finishing point of the journey
	 * @return \Point
	 */
	private function getLastPoint()
	{
		return $this->points[$this->getPointCount() - 1];
	}

}
