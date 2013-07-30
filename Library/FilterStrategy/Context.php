<?php

class FilterStrategyContext implements FilterStrategyInterface
{

	private $strategy = NULL;
	
	/**
	 * 
	 * @param type $strategyId
	 * @throws InvalidFilterStrategy
	 */
	public function __construct($strategyId)
	{
		switch ($strategyId) {
			case 'haversine':
				$this->strategy = new HaversineFilterStrategy();
				break;
			default:
				throw new InvalidFilterStrategyException('Invalid filter strategy');
		}
	}
	
	/**
	 * 
	 * @return type
	 */
	public function getStrategy()
	{
		return $this->strategy;
	}

	/**
	 * 
	 * @param array $points
	 * @param type $buffer
	 * @return array
	 */
	public function filterPoints(array &$points, $buffer)
	{
		return $this->strategy->filterPoints($points, $buffer);
	}

}