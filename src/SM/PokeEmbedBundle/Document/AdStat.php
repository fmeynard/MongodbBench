<?php 
namespace SM\PokeEmbedBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use SM\BenchBundle\Document\MetricTrait;

/**
 * @MongoDB\Document
 */
class AdStat 
{
	use MetricTrait;

	/**
	 * @MongoDB\Id
	 */
	protected $id;

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="d")
	 */
	protected $date;

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

	public function setDate($value)
	{
		$this->date = $value;
	}

	public function getDate()
	{

		return $this->date;
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