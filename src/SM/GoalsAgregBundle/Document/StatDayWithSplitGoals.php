<?php 
namespace SM\GoalsAgregBundle\Document;

use SM\BenchBundle\Document\StatDay;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class StatDayWithSplitGoals extends StatDay 
{
	/**
	 * @MongoDB\Hash
	 * @MongoDB\Field(name="g")
	 */
	protected $goals;

	/**
	 * Setter goals 
	 *
	 * @param array $value
	 */
	public function setGoals($value)
	{
		$this->goals = $value;
	}

	/**
	 * Getter goals
	 *
	 * @return array
	 */
	public function getGoals()
	{
		if (isset($this->goals)) {

			return $this->goals;
		}

		return [];
	}

	/**
	 * Add given goals to current goals array
	 */
	public function addGoal($goals, $key = false)
	{
		if (false == isset($this->goals)) {
			$this->goals = [];
		}

		if (false === $key) {
			$this->goals[] = $goals;
		} elseif (isset($this->goals[$key])) {
			$this->goals[$key]['goals_number'] += $goals['goals_number'];
			$this->goals[$key]['goals_amount'] += $goals['goals_amount'];
		} else {
			$this->goals[$key] = $goals;
		}
	}
}