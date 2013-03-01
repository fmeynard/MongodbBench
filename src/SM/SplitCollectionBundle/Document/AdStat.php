<?php 
namespace Sm\SplitCollectionBundle\Document;

use SM\PokeEmbedBundle\Document\AdStat as BaseAdStat;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use SM\BenchBundle\Document\MetricTrait;

/**
 * @MongoDB\Document(collection="adstatsplit")
 */
class AdStat extends BaseAdStat
{
}