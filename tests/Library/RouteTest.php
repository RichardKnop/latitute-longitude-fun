<?php

class RouteTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException FileNotFoundException
	 * @expectedExceptionMessage CSV file not found
	 */
	public function testConstructorThrowsExceptionIfCsvPathInvalid()
	{
		new Route('bogus_path');
	}

	public function testParseCvRemovesDuplicates()
	{
		$route = new Route(BASE_PATH . '/tests/mocks/points.csv');
		$route->parseCsv();
		$points = $route->getPoints();
		$this->assertCount(3, $points);
	}

	public function testParseCvCreatesCorrectPoints()
	{
		$route = new Route(BASE_PATH . '/tests/mocks/points.csv');
		$route->parseCsv();
		$points = $route->getPoints();

		$this->assertEquals($this->getExpectedPoints(), $points);
	}

	public function testSortPointsByTimestamp()
	{
		$route = new Route(BASE_PATH . '/tests/mocks/points.csv');
		$route->parseCsv();
		$route->sortPointsByTimestamp();
		$points = $route->getPoints();

		$this->assertEquals($this->getExpectedSortedPoints(), $points);
	}

	private function getExpectedPoints()
	{
		$point1 = new Point();
		$point1->setLatitude('51.498041549679');
		$point1->setLongitude('-0.16053670343517');
		$point1->setTimestamp('1326378733');

		$point2 = new Point();
		$point2->setLatitude('51.498405862027');
		$point2->setLongitude('-0.16040688237893');
		$point2->setTimestamp('1326378723');

		$point3 = new Point();
		$point3->setLatitude('51.498205021215');
		$point3->setLongitude('-0.16062694283829');
		$point3->setTimestamp('1326378728');

		return array($point1, $point2, $point3);
	}

	private function getExpectedSortedPoints()
	{
		$point1 = new Point();
		$point1->setLatitude('51.498041549679');
		$point1->setLongitude('-0.16053670343517');
		$point1->setTimestamp('1326378733');

		$point2 = new Point();
		$point2->setLatitude('51.498405862027');
		$point2->setLongitude('-0.16040688237893');
		$point2->setTimestamp('1326378723');

		$point3 = new Point();
		$point3->setLatitude('51.498205021215');
		$point3->setLongitude('-0.16062694283829');
		$point3->setTimestamp('1326378728');

		return array($point2, $point3, $point1);
	}

}