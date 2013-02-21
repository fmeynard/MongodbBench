<?php
namespace SM\GoalsAgregBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GoalsPerfsCommand extends BaseReduceGoalsCommand
{

	/**
	 * @inherited
	 */
	public function configure()
	{
		parent::configure();

		$this->addOption('uniqueCollection', null, InputOption::VALUE_OPTIONAL, '', true);
	}

	/**
	 * @inherited
	 */
	public function start(InputInterface $input, OutputInterface $output)
	{
		if ($input->getOption('uniqueCollection')) {
			$this->executeUniqueCollection($input, $output);
		} else {
			parent::start($input, $output);
		}
	}


	/**
	 * @inherited
	 */
	protected function getCmdName()
	{
		return 'bench:goals:perfs';
	}

	/**
	 * @inherited
	 */
	protected function generateStats() 
	{
		$this->generateStatDayWithSplitGoals();
		$this->generateSingleGoals();
	}

	/**
	 * @inherited
	 */
	protected function executeMR(InputInterface $input, OutputInterface $output)
	{

	}

	/**
	 * @inherited
	 */
	protected function executeAG(InputInterface $input, OutputInterface $output)
	{
		$dm = $this->getDocumentManager();

		$conn = $dm->getConnection();
        if(!$conn->isConnected()) {
        	$conn->connect();
        } 
        $mongoClient = $conn->getMongo();

		// Stats 
        $coll = $mongoClient->selectDB('bench')->selectCollection("StatDayWithSplitGoals");

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
		$stats = $coll->aggregate($ops);

		// Goals
		$ops = array(
				array(
					'$group' => array(
						'_id' => array('adId'=>'$adId', 'objid' => '$obId'),
						'gnb' => array('$sum'=> '$gnb'),
						'gnb' => array('$sum'=> '$gnb'),
						'gna' => array('$sum'=> '$gnb'),
						)
					)
			);

		$goals = $coll->aggregate($ops);

		$output->writeln('Stats count ::'.count($stats));
		$output->writeln('goals count ::'.count($goals));
	}

	/**
	 * Execute Unique collection 
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	public function executeUniqueCollection(InputInterface $input, OutputInterface $output)
	{
		$dm = $this->getDocumentManager();

		$conn = $dm->getConnection();
        if(!$conn->isConnected()) {
        	$conn->connect();
        } 
        $mongoClient = $conn->getMongo();

		// Stats 
        $coll = $mongoClient->selectDB('bench')->selectCollection("StatDayWithSplitGoals");

        $start = microtime(true);

        $ops = [];
		$ops[] = array(
					'$group' => array(
						'_id' => '$adId',
						'c'   => array('$sum' => '$c'),
						'i'   => array('$sum' => '$i'),
						'c'   => array('$sum' => '$c'),
						's'   => array('$sum' => '$s'),
						'sc'  => array('$sum' => '$sc'),
						'uc'  => array('$sum' => '$uc'),
						'suc' => array('$sum' => '$suc'),
						'g'   => array('$addToSet' => '$g')
						)
					);
		$ops[] = array('$unwind' => '$g');
		$ops[] = array('$unwind' => '$g');
		$ops[] = array(
			'$group' => array(
				'_id' => array(
						'adId' => '$_id',
						'ccc'  => '$g.ccc',
						'i'   => '$i',
						'c'   => '$c',
						's'   => '$s',
						'sc'  => '$sc',
						'uc'  => '$uc',
						'suc' => '$suc',
					),
				'gna' => array('$sum' => '$g.gna'),
				'gnb' => array('$sum' => '$g.gnb')	
				)
			);

		$ops[] = array(
			'$group' => array(
					'_id' => array(
						'adId' => '$_id.adId',
						'i'    => '$_id.i',
						'c'    => '$_id.c',
						's'    => '$_id.s',
						'sc'   => '$_id.sc',
						'uc'   => '$_id.uc',
						'suc'  => '$_id.suc',
					),
					'g'   => array(
							'$addToSet' => array(
									'ccc' => '$_id.ccc',
									'gna' => '$gna',
									'gnb' => '$gnb'
								)
						),
				)
			);
		$res = $coll->aggregate($ops);

		$end = microtime(true);
		$output->writeln('AG Executed : ' . $end - $start);
	}

	/**
	 * @inherited
	 */
	protected function executePHP(InputInterface $input, OutputInterface $output)
	{

	}
}