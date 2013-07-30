<?php

class FilterStrategyContextTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException InvalidFilterStrategyException
	 * @expectedExceptionMessage Invalid filter strategy
	 */
	public function testConstructorThrowsExceptionWhenInvalidStrategyId()
	{
		new FilterStrategyContext('bogus');
	}

	public function testHaversineIsValidStrategy()
	{
		$context = new FilterStrategyContext('haversine');
		$this->assertTrue($context->getStrategy() instanceof HaversineFilterStrategy);
	}

}