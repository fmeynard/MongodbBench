<?php 
namespace SM\SplitCollectionBundle\Document;

use SM\PokeEmbedBundle\Document\GoalStat as BaseGoalStat;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="goalstatsplit")
 */
class GoalStat extends BaseGoalStat
{

	/**
	 * @MongoDB\Id
	 */
	protected $id;
}