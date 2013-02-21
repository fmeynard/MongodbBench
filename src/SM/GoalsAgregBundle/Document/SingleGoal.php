<?php 
namespace SM\GoalsAgregBundle\Document;

use SM\BenchBundle\Document\GoalTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class SingleGoal
{
	use GoalTrait;

	/**
	 * @MongoDB\Id
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 */
	protected $adId;

	/**
	 * Setter id 
	 *
	 * @param \MongoId $value
	 */
	public function setId($value)
	{
		$this->id = $value;
	}

	/**
	 * Getter id 
	 *
	 * @return \MongoId
	 */
	public function getId()
	{

		return $this->id;
	}

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