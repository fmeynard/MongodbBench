<?php 
namespace SM\GoalsAgregBundle\Document;

class SingleGoals 
{
	use GoalTrait;

	/**
	 * @MongoDB\String
	 */
	protected $adId;

	/**
	 * Setter : AdId
	 *
	 * @param String $value
	 */
	public function setAdId($value)
	{
		$this->adId = $value;
	}

	/**
	 * Getter : AdId
	 *
	 * @return String
	 */
	public function getAdId()
	{

		return $this->adId;
	}
}