<?php

class HaversineFormulaTest extends PHPUnit_Framework_TestCase
{

	public function testCalculateDistance()
	{
		$haversine = new HaversineFormula();

		$london = new Point();
		$london->setLatitude('51.5171');
		$london->setLongitude('-0.1062');

		$newYork = new Point();
		$newYork->setLatitude('40.7142');
		$newYork->setLongitude('74.0064');

		$distance = $haversine->calculateDistance($london, $newYork);

		$expectedDistance = 5585; // km

		$this->assertEquals($expectedDistance, round($distance));
	}

}