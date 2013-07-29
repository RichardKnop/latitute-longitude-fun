<?php

class Point
{

	private $latitute;
	private $longitude;
	private $timestamp;

	/**
	 * Sets latitude
	 * @param type $latidute
	 */
	public function setLatitude($latidute)
	{
		$this->latitute = $latidute;
	}

	/**
	 * Sets longitude
	 * @param type $longitude
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}

	/**
	 * Sets timestamp
	 * @param type $timestamp
	 */
	public function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

	/**
	 * Gets latitude
	 * @return type
	 */
	public function getLatitude()
	{
		return $this->latitute;
	}

	/**
	 * Gets longitude
	 * @return type
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	/**
	 * Gets timestamp
	 * @return type
	 */
	public function getTimestamp()
	{
		return $this->timestamp;
	}

}