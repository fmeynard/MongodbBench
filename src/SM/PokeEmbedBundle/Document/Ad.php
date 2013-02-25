<?php
namespace SM\PokeEmbedBundle;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Ad
{

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="createdAt")
	 */
	protected $createdAt;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="c")
	 */
	protected $creative;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="fId")
	 */
	protected $facebookId;

	/**
	 * @MongoDB\Boolean
	 * @MongoDB\Field(name="isA")
	 */
	protected $isArchived;

	/**
	 * @MongoDB\Boolean
	 * @MongoDB\Field(name="isD")
	 */
	protected $isDeleted;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="mId")
	 */
	protected $metaId;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="n")
	 */
	protected $name;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="t")
	 */
	protected $target;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="pt")
	 */
	protected $payType;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="st")
	 */
	protected $status;

	/**
	 * @MongoDB\Int
	 * @MongoDB\FIeld(name="syncSt")
	 */
	protected $synchroStatus;

	public function setCreatedAt($value)
	{
		$this->createdAt = $value;
	}

	public function getCreatedAt()
	{

		return $this->createdAt;
	}

	public function setCreative($value)
	{
		$this->creative = $value;
	}

	public function getCreative()
	{

		return $this->creative;
	}

	public function setFacebookId($value)
	{
		$this->facebookId = $value;
	}

	public function getFacebookId()
	{

		return $this->facebookId;
	}

	public function setIsArchived($value)
	{
		$this->isArchived = $value;
	}

	public function getIsArchived()
	{

		return $this->isArchived;
	}

	public function setIsDeleted($value)
	{
		$this->isDeleted = true;
	}

	public function getIsDeleted()
	{

		return $this->isDeleted;
	}

	public function setMetaId($value)
	{
		$this->metaId = $value;
	}

	public function getMetaId()
	{

		return $this->metaId;
	}

	public function setName($value)
	{

		$this->name = $value;
	}

	public function getName()
	{

		return $this->name;
	}

	public function setTarget($value)
	{
		$this->target = $target;		
	} 

	public function getTarget()
	{

		return $this->target;
	}

	public function setPayType($value)
	{
		$this->payType = $value;
	}

	public function getPayType()
	{

		return $this->payType;
	}

	public function setStatus($value)
	{
		$this->status = $value;
	}

	public function getStatus()
	{

		return $this->status;
	}

	public function setSynchroStatus($value)
	{
		$this->syncrhoStatus = $value;
	}

	public function getSynchroStatus()
	{

		return $this->synchroStatus;
	}
}