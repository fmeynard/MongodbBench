<?php 
namespace SM\PokeEmbedBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use SM\BenchBundle\Document\GoalTrait;

class GoalStat 
{
	use GoalTrait;

	/**
	 * @MongoDB\Date
	 * @MongoDB\Field(name="d")
	 */
	protected $date;

	public function setDate($value)
	{
		$this->date = $value;
	}

	public function getDate()
	{

		return $this->date;
	}
}