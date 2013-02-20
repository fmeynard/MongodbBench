<?php 
namespace SM\GoalsAgregBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GoalsReduceSplitCommand extends BaseReduceGoalsCommand
{

	/**
	 * @inherited
	 */
	protected function getCmdName()
	{
		return 'bench:goals:reduceSplit';
	}

	/**
	 * @inherited
	 */
	protected function executeAG(InputInterface $input, OutputInterface $output)
	{
		$output->writeln("Agregate framework");

		$dm = $this->getDocumentManager();

		$ops = array(
				array(
					'$group' => array(
						'_id' => '$adId',
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc')
						)
					)
			);


		$conn = $dm->getConnection();
        if(!$conn->isConnected()) $conn->connect();
 
        $mongoClient=$conn->getMongo();

        $coll=$mongoClient->selectDB('bench')->selectCollection("StatDayWithSplitGoals");
		$out = $coll->aggregate($ops);

		$output->writeln(print_r($out));
	}

	/**
	 * @inherited
	 */
	protected function executeMR(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('MAP REDUCCCCCCCCCCCCCCCCCCCCCEEEEEEEEEEEEEEEE');

		$dm = $this->getDocumentManager();

		$map = '
			function() {
				emit(this.adId, {
					i: this.i,
					c: this.c,
					s: this.s,
					sc: this.sc,
					uc: this.uc,
					suc: this.suc
				});
			}
		';

		$reduce = '
			function(k, vals) {
				var sum = {
					i: 0,
					c: 0,
					s: 0,
					sc: 0,
					uc: 0,
					suc: 0
				};

				for(i in vals) {
					sum.i += vals[i].i;
					sum.c += vals[i].c;
					sum.s += vals[i].s;
					sum.sc += vals[i].sc;
					sum.uc += vals[i].uc;
					sum.suc += vals[i].suc;
				}

				return sum;
			}
		';
		$start = microtime(true);

		$results = $dm->createQueryBuilder('SMGoalsAgregBundle:StatDayWithSplitGoals')
			->map($map)
			->reduce($reduce)
			->hydrate(false)
			->getQuery()
			->execute();

		$end = microtime(true) - $start;
		$output->writeln("With doctrine :: " .$end);

		$start = microtime(true);
		$con = $dm->getConnection();
		$con->connect();

		$res = $con->selectDatabase('bench')->command(array( 
		     'mapreduce' => 'StatDayWithSplitGoals', 
		     'map' => $map, 
		     'reduce' => $reduce, 
		     'out' => 'mapReduceEventStats' 
		)); 
		$end2 = microtime(true) - $start;
		$output->writeln("Without doctrine :: " .$end2);
		$output->writeln('Ratio : ' . $end / $end2 * 100);
	}

	/**
	 * @inherited
	 */
	protected function executePHP(InputInterface $input, OutputInterface $output)
	{
		$dm = $this->getDocumentManager();

		$conn = $dm->getConnection();
        if(!$conn->isConnected()) $conn->connect();
 
        $mongoClient = $conn->getMongo();

        $coll = $mongoClient->selectDB('bench')->selectCollection("StatDayWithSplitGoals");
        $results = $coll->find();

        $sum = array();

        foreach ($results as $result) {
        	if (false == isset($sum[$result['adId']])) {
        		$sum[$result['adId']] = array(
        				'i'   => 0,
        				'c'   => 0,
        				's'   => 0,
        				'sc'  => 0,
        				'uc'  => 0,
        				'suc' => 0
        			);
        	}

        	$sum[$result['adId']]['i'] += $result['i'];
        	$sum[$result['adId']]['c'] += $result['c'];
        	$sum[$result['adId']]['s'] += $result['s'];
        	$sum[$result['adId']]['sc'] += $result['sc'];
        	$sum[$result['adId']]['uc'] += $result['uc'];
        	$sum[$result['adId']]['suc'] += $result['suc'];
        }

        $output->writeln(print_r($sum));
	}

	/**
	 * @inherited
	 */
	protected function generateStats()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$output->writeln('Cleaning collection');

        $coll = $this->getDocumentManager()->getDocumentCollection('SMGoalsAgregBundle:StatDayWithSplitGoals');
        $coll->remove(array()); 

        $start = microtime(true);
        $currents = [];

        $batchInsert = function(&$currents, &$coll) {
            $coll->batchInsert($currents, array(
                    "w" => 0,
                    "j" => 0
                ));
        };

        for($i=1;$i<$input->getOption('nbStat');$i++)
        {
            $current = array(
                    'adId' => rand(1, $input->getOption('nbAds')),
                    'metaId' => rand(1, $input->getOption('nbAds')/100),
                    'c' => 10 + $i,
                    's' => 20 + $i,
                    'sc' => 30 + $i,
                    'uc' => 40 + $i,
                    'suc' => 50 + $i,
                    'i' => 60 + $i,
                    'g' => array(
                    		'fb|like|apple' => array(
                    			'gna' => 2 * $i,
                    			'gnb' => $i
                    		),
                    		'fb|like|microsoft' => array(
                    			'gna' => 3 * $i,
                    			'gnb' => $i
                    		)
                    	)
                );

            $currents[] = $current;

            if (count($currents) >= $input->getOption('chunk')) {
                $batchInsert($currents, $coll);
                $currents = [];

                $output->writeln("Done : ".$i."/".$input->getOption('nbStat'));
            }
        }

        if (count($currents) > 0) {
            $batchInsert($currents, $coll);
        }

        $end = microtime(true) - $start;

        $output->writeln('Executed in ' .$end);
	}
}