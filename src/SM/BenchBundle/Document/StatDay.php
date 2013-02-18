<?php 
namespace SM\BenchBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class StatDay 
{

	use MetricTrait;
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
	 * @MongoDB\String
	 */
	protected $metaId;

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="start")
	 */
	protected $date;

	/****************************
	 * 
	 * DATABASE GETTERS & SETTERS
	 *
	 ***************************/

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getId()
	{

		return $this->id;
	}

	public function setAdId($value)
	{
		$this->adId = $value;
	}

	public function getAdId()
	{

		return $this->adId;
	}

	public function setMetaId($value)
	{
		$this->metaId;
	}

	public function getMetaId()
	{

		return $this->metaId;
	}

	public function setFacebookId($value)
	{
		$this->facebookId = $value;
	}

	public function getFacebookId()
	{

		return $this->facebookId;
	}


}