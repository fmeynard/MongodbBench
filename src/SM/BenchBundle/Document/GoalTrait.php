<?php
namespace SM\BenchBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait GoalTrait
{
	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="gnb")
	 */
	protected $goals_number;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="gna")
	 */
	protected $goals_amount;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="gnt")
	 */
	protected $goals_type;

	/****************************
	 * 
	 * DATABASE GETTERS & SETTERS
	 *
	 ***************************/

	public function setGoalsNumber($value)
	{
		$this->goals_number = $value;
	}

	public function getGoalsNumber()
	{
		if (isset($this->goals_number)) {

			return $this->goals_number;
		}

		return 0;
	}

	public function setGoalsAmount($value)
	{
		$this->goals_amount = $value;
	}

	public function getGoalsAmount()
	{
		if (isset($this->goals_amount)) {

			return $this->goals_amount;
		}

		return 0;
	}

	public function setGoalsType($value)
	{
		$this->goals_type = $value;
	}

	public function getGoalsType()
	{
		if (isset($this->goals_type)) {

			return $this->goals_type;
		}

		return 0;
	}
}