<?php
namespace SM\BucketPokeBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use SM\BenchBundle\Document\MetricTrait;

/**
 * @MongoDB\Document
 */
class StatBucket 
{
	use MetricTrait;

	/**
	 * @MongoDB\Id
	 */
	protected $id;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="db")
	 */
	protected $dateBucket;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="fId")
	 */
	protected $facebookId;

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getId()
	{

		return $this->id;
	}

	public function setDateBucket($value)
	{
		$this->dateBucket = $value;
	}

	public function getDateBucket()
	{

		return $this->dateBucket;
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