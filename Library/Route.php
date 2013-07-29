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
	 * Gets points
	 * @return array
	 */
	public function getPoints()
	{
		return $this->points;
	}

}
