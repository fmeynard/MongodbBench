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
	static public $FIELD_AD_ID = 'adId';

	/**
	 * @MongoDB\String
	 */
	protected $metaId;
	static public $FIELD_META_ID = 'metaId';

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="start")
	 */
	protected $date;
	static public $FIELD_START = 'start';

	/****************************
	 * 
	 * DATABASE GETTERS & SETTERS
	 *
	 ***************************/

	/**
	 * Setter : id
	 *
	 * @param mixed $value
	 */
	public function setId($value)
	{
		$this->id = $value;
	}

	/**
	 * Getter : id
	 *
	 * @return MongoId
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Setter : AdId
	 *
	 * @param string $value
	 */
	public function setAdId($value)
	{
		$this->adId = $value;
	}

	/**
	 * Getter : AdId
	 *
	 * @return string
	 */
	public function getAdId()
	{

		return $this->adId;
	}

	/**
	 * Setter : metaId
	 *
	 * @param string $value
	 */
	public function setMetaId($value)
	{
		$this->metaId = $value;
	}

	/**
	 * Getter : metaId
	 *
	 * @return string
	 */
	public function getMetaId()
	{

		return $this->metaId;
	}

	/**
	 * Setter : FacebookId
	 *
	 * @param string $facebookId
	 */
	public function setFacebookId($value)
	{
		$this->facebookId = $value;
	}

	/**
	 * Getter : FacebookId
	 *
	 * @return string
	 */
	public function getFacebookId()
	{

		return $this->facebookId;
	}
}