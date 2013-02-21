<?php
namespace SM\GoalsAgregBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use SM\BenchBundle\Document\MetricTrait;


/**
 * @MongoDB\Document
 */
class StatDayFull 
{
	use MetricTrait;
}