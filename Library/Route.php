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
		$filterStrategyContext = new FilterStrategyContext('haversine');

		$this->points = $filterStrategyContext->filterPoints($this->points, $buffer);
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

}
