<?php
namespace SM\BenchBundle\Document;

class AdWithoutStatDay 
{
	protected $id;
	protected $name;
	protected $created_at;
	protected $facebookId;

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getId()
	{

		return $this->id;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setCreatedAt($value)
	{

		$this->created_at = $value;
	}

	public function getCreatedAt()
	{

		return $this->created_at;
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