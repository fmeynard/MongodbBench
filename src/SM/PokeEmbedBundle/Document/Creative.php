<?php 
namespace SM\PokeEmbedBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Creative 
{
	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="ap")
	 */
	protected $actionSpec;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="au")
	 */
	protected $autoUpdate;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="apt")
	 */
	protected $appPlatformType;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="c")
	 */
	protected $context;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="d")
	 */
	protected $description;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="oid")
	 */
	protected $objectId;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="i")
	 */
	protected $image;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="ih")
	 */
	protected $imageHash;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="in")
	 */
	protected $imageName;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="storyId")
	 */
	protected $storyId;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="t")
	 */
	protected $title;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="ty")
	 */
	protected $type;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="tr")
	 */
	protected $tracking;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="u")
	 */
	protected $url;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="ut")
	 */
	protected $urlTags;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="p")
	 */
	protected $preview;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="vt0")
	 */
	protected $viewTag0;

	/**
	 * @MongoDB\String
	 * @MongoDB\Field(name="vt1")
	 */
	protected $viewTag1;

	/**
	 * Setter : action spec
	 * 
	 * @param String $value
	 */
	public function setActionSpec($value)
	{
		$this->actionSpec = $value;
	}

	/**
	 * Getter : action spec
	 *
	 * @return String
	 */
	public function getActionSpec()
	{

		return $this->actionSpec;
	}

	/**
	 * Setter : autoUpdate
	 *
	 * @param String $value
	 */
	public function setAutoUpdate($value)
	{
		$this->autoUpdate = $value;
	}

	/**
	 * Getter : autoUpdate
	 * 
	 * @return String
	 */
	public function getAutoUpdate()
	{

		return $this->autoUpdate;
	}

	/**
	 * Setter : app platform type
	 *
	 * @param String $value
	 */
	public function setAppPlatformType($value)
	{
		$this->appPlatformType = $value;
	}

	/**
	 * Getter : app platform type
	 *
	 * @return String
	 */
	public function getAppPlatformType()
	{

		return $this->appPlatformType;
	}

	/**
	 * Setter : context
	 *
	 * @param String $value
	 */
	public function setContext($value)
	{
		$this->context = $value;
	}

	/**
	 * Getter : context
	 *
	 * @return String
	 */
	public function getContext()
	{

		return $this->context;
	}

	/**
	 * Setter : description
	 *
	 * @param String
	 */
	public function setDescription($value)
	{
		$this->description = $value;
	}

	/**
	 * Getter : description
	 * 
	 * @return String
	 */
	public function getDescription()
	{

		return $this->description;
	}

	/**
	 * Setter : object Id
	 * 
	 * @param String $value 
	 */
	public function setObjectId($value)
	{
		$this->objectId = $value;
	}

	/**
	 * Getter : object id
	 *
	 * @return String
	 */
	public function getObjectId()
	{

		return $this->objectId;
	}

	/**
	 * Setter : image
	 *
	 * @param String $value
	 */
	public function setImage($value)
	{
		$this->image = $value;
	}

	/**
	 * Getter : image
	 *
	 * @return String
	 */
	public function getImage()
	{

		return $this->image ;
	}

	/**
	 * Setter : image name
	 *
	 * @param String $value
	 */
	public function setImageName($value)
	{
		$this->imageName = $value;
	}

	/**
	 * Getter : image name
	 *
	 * @return String
	 */
	public function getImageName()
	{

		return $this->imageName;
	}

	/**
	 * Setter : image hash 
	 *
	 * @param String $value
	 */
	public function setImageHash($value)
	{
		$this->imageHash = $value;
	}

	/**
	 * Getter : image hash
	 *
	 * @return String 
	 */
	public function getImageHash()
	{

		return $this->imageHash;
	}

	/**
	 * Setter : story id
	 *
	 * @param String $value
	 */
	public function setStoryId($value)
	{
		$this->storyId = $value;
	}

	/**
	 * Getter : story id
	 *
	 * @return String
	 */
	public function getStoryId()
	{

		return $this->storyId;
	}

	/**
	 * Setter : title
	 *
	 * @param String $value
	 */
	public function setTitle($value)
	{
		$this->title = $value;
	}

	/**
	 * Getter : title
	 *
	 * @return String
	 */
	public function getTitle()
	{

		return $this->title;
	}

	/**
	 * Setter : type
	 *
	 * @param String $value
	 */
	public function setType($value)
	{
		$this->type = $value;
	}

	/**
	 * Getter : type
	 *
	 * @return String
	 */
	public function getType()
	{

		return $this->type;
	}

	/**
	 * Setter : tracking
	 *
	 * @param String $value
	 */
	public function setTracking($value)
	{
		$this->tracking = $value;
	}

	/**
	 * Getter : tracking
	 *
	 * @return String
	 */
	public function getTracking()
	{

		return $this->tracking;
	}

	/**
	 * Setter : url 
	 *
	 * @param String $value
	 */
	public function setUrl($value)
	{
		$this->url = $value;
	}

	/**
	 * Getter : url
	 *
	 * @return String
	 */
	public function getUrl()
	{

		return $this->url;
	}

	/**
	 * Setter url tags
	 *
	 * @param String $value
	 */
	public function setUrlTags($value)
	{
		$this->urlTags = $value;
	}

	/**
	 * Getter : url tags
	 *
	 * @return String
	 */
	public function getUrlTags()
	{

		return $this->urlTags;
	}

	/**
	 * Setter : preview
	 *
	 * @param String $value
	 */
	public function setPreview($value)
	{
		$this->preview = $value;
	}

	/**
	 * Getter : preview 
	 *
	 * @return String
	 */
	public function getPreview()
	{

		return $this->preview;
	}

	/**
	 * Setter : views tags 0
	 *
	 * @param String $value
	 */
	public function setViewTag0($value)
	{
		$this->viewTag0 = $value;
	}

	/**
	 * Getter : views tags 0
	 *
	 * @return String 
	 */
	public function getViewTag0()
	{

		return $this->viewTag0;
	}

	/**
	 * Setter : views tags 1
	 *
	 * @param String $value
	 */
	public function setViewTag1($value)
	{
		$this->viewTag1 = $value;
	}

	/**
	 * Getter : views tags 1
	 *
	 * @return String 
	 */
	public function getViewTag1()
	{

		return $this->viewTag1;
	}
}