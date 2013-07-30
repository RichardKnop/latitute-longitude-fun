<?php

interface FilterStrategyInterface
{

	public function filterPoints(array &$points, $buffer);
	
}