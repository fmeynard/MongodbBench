<?php
namespace SM\BenchBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait MetricTrait 
{
	/**
	 * @MongoDB\Float
	 * @MongoDB\Field(name="s")
	 */
	protected $spent;
	static public $FIELD_SPENT = 's';

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="c")
	 */
	protected $click;
	static public $FIELD_CLICK = 'click';

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="sc")
	 */
	protected $socialClick;
	static public $FIELD_SOCIAL_CLICK = 'sc';

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="uc")
	 */
	protected $uniqueClick;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="suc")
	 */
	protected $socialUniqueClick;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="i")
	 */
	protected $impression;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="si")
	 */
	protected $socialImpression;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="ui")
	 */
	protected $uniqueImpression;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="sui")
	 */
	protected $socialUniqueImpression;

	/**
	 * @MongoDB\Float
	 * @MongoDB\Field(name="cp")
	 */
	protected $clickPayout;

	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="a")
	 */
	protected $actions;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="nfi")
	 */
	protected $newsfeed_impression;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="nfc")
	 */
	protected $newsfeed_click;

	/**
	 * @MongoDB\Int
	 * @MongoDB\Field(name="nfp")
	 */
	protected $newsfeed_position;

	/****************************
	 * 
	 * DATABASE GETTERS & SETTERS
	 *
	 ***************************/

	/**
	 * Setter : spent
	 *
	 * @param float $value
	 */
	public function setSpent($value)
	{
		$this->spent = $value;
	}

	/**
	 * Getter : spent
	 *
	 * @return float
	 */
	public function getSpent()
	{
		if (isset($this->spent)) {

			return $this->spent;
		}

		return 0;
	}


	/**
	 * Setter : click
	 *
	 * @param int $value Click value
	 */
	public function setClick($value)
	{
		$this->click = $value;
	}

	/**
	 * Getter : click
	 *
	 * @return int
	 */
	public function getClick()
	{
		if (isset($this->click)) {

			return $this->click;
		}

		return 0;
	}

	/**
	 * Setter : social click
	 *
	 * @param int $value
	 */
	public function setSocialClick($value) 
	{
		$this->socialClick = $value;
	}

	/**
	 * Getter : social click
	 *
	 * @return int
	 */
	public function getSocialClick()
	{
		if (isset($this->socialClick)) {

			return $this->socialClick;
		}

		return 0;
	}

	/**
	 * Setter : social unique click
	 *
	 * @param int $value
	 */
	public function setSocialUniqueClick($value)
	{
		$this->socialUniqueClick = $value;
	}

	/**
	 * Getter : social unique click
	 *
	 * @return int
	 */
	public function getSocialUniqueClick()
	{
		if (isset($this->socialUniqueClick)) {

			return $this->socialUniqueClick;
		}

		return 0;
	}

	/**
	 * Setter : unique click
	 * 
	 * @param int $value Unique click value
	 */
	public function setUniqueClick($value)
	{
		$this->uniqueClick = $value;
	}

	/**
	 * Getter : unique click
	 * 
	 * @return int
	 */
	public function getUniqueClick()
	{
		if (isset($this->uniqueClick)) {

			return $this->uniqueClick;
		}

		return 0;
	}

	/**
	 * Setter : impression
	 *
	 * @param int $value
	 */
	public function setImpression($value)
	{
		$this->impression = $value;
	}

	/**
	 * Getter : impression 
	 *
	 * @return int
	 */
	public function getImpression()
	{
		if (isset($this->impression)) {

			return $this->impression;
		}

		return 0;
	}

	/**
	 * Setter : unique impression
	 *
	 * @param int $value
	 */
	public function setUniqueImpression($value)
	{
		$this->uniqueImpression = $value;	
	}

	/**
	 * Getter : unique impression
	 *
	 * @return int
	 */
	public function getUniqueImpression()
	{
		if (isset($this->uniqueImpression)) {

			return $this->uniqueImpression;
		}

		return 0;
	}

	/**
	 * Setter : social impression
	 *
	 * @param int $value
	 */
	public function setSocialImpression($value)
	{
		$this->socialImpression = $value;
	}

	/**
	 * Getter : social impression
	 *
	 * @return int 
	 */
	public function getSocialImpression()
	{
		if (isset($this->uniqueImpression)) {

			return $this->uniqueImpression;
		}

		return 0;
	}

	/**
	 * Setter : social unique impression
	 *
	 * @param int
	 */
	public function setSocialUniqueImpression($value)
	{
		$this->socialUniqueImpression = $value;
	}

	/**
	 * Getter : social unique impression
	 * 
	 * @return int
	 */
	public function getSocialUniqueImpression()
	{
		if (isset($this->socialUniqueImpression)) {

			return $his->socialUniqueImpression;
		}

		return 0;
	}

	/**
	 * Setter : click payout
	 *
	 * @param float $value
	 */
	public function setClickPayout($value)
	{
		$this->clickPayout = $value;
	}

	/**
	 * Getter : click payout
	 *
	 * @return float
	 */
	public function getClickPayout()
	{
		if (isset($this->clickPayout)) {

			return $this->clickPayout;
		}

		return 0;
	}

	/**
	 * Setter : actions
	 *
	 * @param array $value
	 */
	public function setActions($value)
	{
		$this->actions = $value;
	}

	/**
	 * Getter : actions 
	 * 
	 * @return array
	 */
	public function getActions()
	{
		if (isset($this->actions)) {

			return $this->actions;
		}

		return 0;
	}

	/**
	 * Setter : newsfeed click
	 *
	 * @param int click
	 */
	public function setNewsfeedClick($value)
	{
		$this->newsfeed_click = $value;
	}

	/**
	 * Getter : newsfeed click
	 *
	 * @return int
	 */
	public function getNewsfeedClick()
	{
		if (isset($this->newsfeed_click)) {

			return $this->newsfeed_click;
		}

		return 0;
	}

	/**
	 * Setter : newsfeed impression
	 *
	 * @param int $value
	 */
	public function setNewsfeedImpression($value)
	{
		$this->newsfeed_impression = $value;
	}

	/**
	 * Getter : newsfeed impression
	 *
	 * @return int
	 */
	public function getNewsfeedImpression()
	{
		if (isset($this->newsfeed_impression)) {

			return $this->newsfeed_impression;
		}

		return 0;
	}

	/**
	 * Setter : newsfeed position
	 *
	 * @param float $value
	 */
	public function setNewsfeedPosition($value)
	{
		$this->newsfeed_position = $value;
	}

	/**
	 * Getter : newsfeed position
	 *
	 * @return float
	 */
	public function getNewsfeedPosition()
	{
		if (isset($this->newsfeed_position)) {

			return $this->newsfeed_position;
		}

		return 0;
	}

	/****************************
	 * 
	 * Custom Getters
	 *
	 ***************************/

	/**
	 * Getter : brut
	 *
	 * @return float 
	 */
	public function getBrut()
	{

		return $this->getClick * $this->getClickPayout;	
	}

	/**
	 * Getter : CTR
	 *
	 * @return float
	 */
	public function getCTR()
	{
		if (0 < $this->getImpression()) {

			return $this->getClick() * 100 / $this->getImpression();
		}

		return 0;
	}

}