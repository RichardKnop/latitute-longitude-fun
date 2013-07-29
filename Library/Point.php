<?php

class Point
{

	private $latitute;
	private $longitude;
	private $timestamp;

	public function setLatitude($latidute)
	{
		$this->latitute = $latidute;
	}

	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}

	public function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

	public function getLatitude()
	{
		return $this->latitute;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}

}