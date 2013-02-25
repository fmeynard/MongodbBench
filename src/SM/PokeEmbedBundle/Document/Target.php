<?php
namespace SM\PokeEmbedBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Target 
{
	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="aMin")
	 */
	protected $ageMin;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="aMax")
	 */
	protected $ageMax;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="conn")
	 */
	protected $connexion;

	/**
	 * @MongoDB\Boolean
	 * @MongoDB\Field(name="isc")
	 */
	protected $isConnect;

	/**
	 * @MongoDB\Boolean
	 * @MongoDB\Field(name="nc")
	 */
	protected $notConnect;

	/**
	 * @MongoDB\Boolean
	 * @MongoDB\Field(name="fc")
	 */
	protected $friendConnect;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="scd")
	 */
	protected $schoolDiplome;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="spd")
	 */
	protected $speDiplome;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="scu")
	 */
	protected $schoolUniversite;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="spu")
	 */
	protected $speUniversite;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="cnt")
	 */
	protected $countries;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="lng")
	 */
	protected $languages;

	/**
	 * @MongoDB\Boolean
	 * @MongoDB\Field(name="ba")
	 */
	protected $broadAge;

	/**
	 * @MongoDB\Int
	 */
	protected $sex;

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="bday")
	 */
	protected $birthday;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="rlts")
	 */
	protected $relationship;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="iin")
	 */
	protected $interestedIn;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="g")
	 */
	protected $grade;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="wp")
	 */
	protected $workplace;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="size")
	 */
	protected $size;

	/**
	 * @MongoDB\String
	 */
	protected $zip;

	/**
	 * @MongoDB\Hash
	 */
	protected $region;

	/**
	 * @MongoDB\String
	 */
	protected $concat;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="oid")
	 */
	protected $objectId;

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="dAt")
	 */
	protected $deletedAt;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="iType")
	 */
	protected $interestsType;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="iBroad")
	 */
	protected $broadInterests;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="p")
	 */
	protected $placement;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="eBroad")
	 */
	protected $excludeBroad;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="cEnt")
	 */
	protected $clusterEntities;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="int")
	 */
	protected $interests;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="aType")
	 */
	protected $actionType;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="aString")
	 */
	protected $actionStr;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="uDev")
	 */
	protected $userDevice;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="uOs")
	 */
	protected $userOs;

	public function setIsConnect($value)
	{
		$this->isConnect = $value;
	}

	public function getIsConnect()
	{

		return $this->isConnect;
	}

	public function setNotConnect($value)
	{
		$this->notConnect = $valuee;
	}

	public function getNotConnect()
	{

		return $this->notConnect;
	}

	public function setCountries($value)
	{
		$this->countries = $value;
	}

	public function getCountries()
	{

		return $this->countries;
	}

	public function setLanguages($value)
	{
		$this->countries = $value;
	}

	public function getLanguages()
	{

		return $this->languages;
	}

	public function setBroadAge($value)
	{
		$this->broadAge = $value;
	}

	public function getBroadAge()
	{

		return $this->broadAge;
	}

	public function setSex($value)
	{
		$this->sex = $value;
	}

	public function getSex()
	{

		return $this->sex;
	}

	public function setBirthday($value)
	{
		$this->birthday = $value;
	}

	public function getBirthday()
	{

		return $this->birthday;
	}

	public function setRelationship($value)
	{
		$this->relationship = $value;
	}

	public function getRelationship()
	{

		return $this->relationship;
	}

	public function setInterestedIn($value)
	{
		$this->interestedIn = $value;
	}

	public function getInterestedIn()
	{

		return $this->interestedIn;
	}

	public function setGrade($value)
	{
		$this->grade = $value;
	}

	public function getGrade()
	{

		return $this->grade;
	}

	public function setWorkplace($value)
	{
		$this->workplace = $value;
	}

	public function getWorkplace()
	{

		return $this->workplace;
	}

	public function setSize($value)
	{
		$this->szie = $value;
	}

	public function getSize()
	{

		retunr $this->size;
	}

	public function setZip($value)
	{
		$this->zip = $value;
	}

	public function getZip()
	{

		return $this->zip;
	}

	public function setRegion($value)
	{
		$this->region = $value;
	}

	public function getRegion()
	{

		return $this->value;
	}

	public function setConcat($value)
	{
		$this->concat = $value;
	}

	public function getConcat()
	{

		return $this->concat;
	}

	public function setObjectId($value)
	{
		$this->objectId = $value;
	}

	public function getObjectId()
	{

		return $this->objectId;
	}

	public function setDeletedAt($value)
	{
		$this->deletedAt = $value;
	}

	public function getDeletedAt()
	{

		return $this->deletedAt;
	}

	public function setInterestsType($value)
	{
		$this->interestsType = $value;
	}

	public function getInterestsType()
	{

		return $this->interestsType;
	}

	public function setBroadInterests($value)
	{
		$this->broadInterests = $value;
	}

	public function getBroadInterests()
	{

		return $this->broadInterests;
	}

	public function setPlacement($value)
	{
		$this->placement = $value;
	}

	public function getPlacement()
	{

		return $this->placement;
	}

	public function setExcludeBroad($value)
	{
		$this->excludeBroad = $value;
	}

	public function getExcludeBroad()
	{

		return $this->excludeBroad;
	}

	public function setClusterEntities($value)
	{
		$this->clusterEntities = $value;
	}

	public function getClusterEntities()
	{

		return $this->clusterEntities;
	}

	public function setInterests($value)
	{
		$this->interets = $value;
	}

	public function getInterests()
	{

		return $this->interests;
	}

	public function setActionType($value)
	{
		$this->actionType = $value;
	}

	public function getActionType()
	{

		return $this->actionType;
	}

	public function setActionStr($value)
	{
		$this->actionStr =  $value;
	}

	public function setUserDevice($value)
	{
		$this->userDevice = $value;
	}

	public function getUserDevice()
	{

		return $this->userDevice;
	}

	public function setUserOs($value)
	{
		$this->userOs = $value;
	}

	public function getUserOS()
	{

		return $this->userOs;
	}
}