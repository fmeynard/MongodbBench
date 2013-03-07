<?php 
namespace SM\SplitCollectionBundle\Command;


use SM\BenchBundle\Command\baseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SM\SplitCollectionBundle\Lib\SMDateInterval;

class AgregByDateCommand extends BaseCommand
{
	protected $description = '';
	protected $help = array(
			'ad'    => '',
			'meta'  => '',
			'start' => '',
			'end'   => '',
			'split' => '',
			'host'  => ''
		);

	public function configure()
	{
		$this->setName('bench:split:reduceTime')
			->setDescription($this->description)
			->addOption('host',  null, InputOption::VALUE_OPTIONAL, $this->help['host'], 'localhost')
			->addOption('ad',    null, InputOption::VALUE_OPTIONAL, $this->help['ad'])
			->addOption('meta',  null, InputOption::VALUE_OPTIONAL, $this->help['meta'])
			->addOption('start', null, InputOption::VALUE_OPTIONAL, $this->help['start'])
			->addOption('end',   null, InputOption::VALUE_OPTIONAL, $this->help['end'])
			->addOption('split2', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split3', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split4', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split5', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split6', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split7', null, InputOption::VALUE_OPTIONAL, $this->help['split']);

	}	

	/**
	 * Execute current command
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$this->setInput($input);
        $this->setOutput($output);

		$output->writeln("****************************");
        $output->writeln("*");
        $output->writeln("* GOALS PERFORMANCES");
        $output->writeln("*");
        $output->writeln("* ad : " .$input->getOption('ad'));
        $output->writeln("* meta : " .$input->getOption('meta'));
        $output->writeln("* start : " .$input->getOption('start'));
        $output->writeln("* end : " .$input->getOption('end'));
        $output->writeln("*");  
        $output->writeln("*");
        $output->writeln("****************************");

        if ($input->getOption('meta')) {
        	$this->agregMetaByDate(
        			$input->getOption('meta'),
        			$input->getOption('start'),
        			$input->getOption('end')
        		);
        } elseif($input->getOption('split2')) {
        	$this->split2();
        } elseif($input->getOption('split3')) {
        	$this->split3();
        } elseif($input->getOption('split4')) {
        	$this->split4();
        } elseif($input->getOption('split5')) {
        	$this->split5();
        } elseif($input->getOption('split6')) {
        	$this->split6();
        } elseif($input->getOption('split7')) {
        	$this->split7();
        }
	}

	/**
	 * Agreg meta by date
	 *
	 * @param String $meta
	 * @param String $start
	 * @param String $end
	 */
	public function agregMetaByDate($meta, $start, $end)
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));

		echo "Agreg By Meta/Date";
		$m    = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db   = $m->selectDB('bench');
		$coll = $db->selectCollection("adstatsplit");
		\MongoCursor::$timeout = 120000;

		$ops = array(
				array(
					'$match' => array(
						'_id.fbId' => array('$lte'=>500),
						'_id.d'    => array('$gte'=>$start),
						//'end'      => array('$lte'=>$end)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'fbId' => '$_id.fbId'
							),
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc')
						)
					)
			);

		$ins = [];
		for($i=1; $i<500; $i++) $i[] = $ins;

		$metrics = $coll->aggregate($ops);

		$coll = $db->selectCollection("goalstatsplit");
		$ops = array(
				array(
					'$match' => array(
						'_id.fbId' => array('$in'=>$ins),
						'_id.d'    => array('$gte'=>$start)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'obid' => '$_id.obid'
							),
						'gnb' => array('$sum'=>'$gnb'),
						'gna' => array('$sum'=>'$gna')
					)
				)
			);

		$goals = $coll->aggregate($ops);

		print_r($goals);
	}

	/**
	 * split 2
	 */
	protected function split2()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));


		$ins = [];
		for($i=1; $i<500; $i++) $i[] = $ins;

		echo "Agreg By Meta/Date";
		$m    = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db   = $m->selectDB('bench');
		\MongoCursor::$timeout = 120000;

		$coll = $db->selectCollection("goalstatsplit");
		$ops = array(
				array(
					'$match' => array(
						'fbId' => array('$in'=>$ins),
						'd'    => array('$gte'=>$start)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'fbId' => '$fbId',
							'd'    => '$d',
							'oid'  => '$oid'
							),
						''
					)
				)
			);

		$goals = $coll->aggregate($ops);
	}

	/**
	 * split 3
	 */
	protected function split3()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));

		echo "<<<< SPLIT 3 AGGREG >>>> \n";
		$m    = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db   = $m->selectDB('bench');

		\MongoCursor::$timeout = 120000;

		$ins = [];
		for($i=1; $i<2; $i++) $ins[] = $i;

		$coll = $db->selectCollection("goalstatsplit3");
		$ops = array(
				array(
					'$match' => array(
						'fbId' => array('$in'=>$ins),
						'd'    => array('$gte'=>$start)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							'd'    => '$d'
							),
						'objects' => array('$addToSet'=> '$objects')
					)
				),
				array('$unwind'=>'$objects'),
				array('$unwind'=>'$objects')
			);

		$goals = $coll->aggregate($ops);

		print_r($goals);
	}

	/**
	 * split 5
	 */
	protected function split5()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));

		echo "<<<< SPLIT 5 AGGREG >>>> \n";
		$m    = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db   = $m->selectDB('bench');

		\MongoCursor::$timeout = 120000;

		$ins = [];
		for($i=1; $i<500; $i++) $ins[] = $i;

		$coll = $db->selectCollection("goalstatsplit5");
		$ops = array(
				array(
					'$match' => array(
						'fbId' => array('$in'=>$ins),
						'd'    => array('$gte'=>$start)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							),
						'goals' => array('$addToSet'=> '$goals')
					)
				),
				array('$unwind'=>'$goals'),
				array('$unwind'=>'$goals'),
				array(
					'$group' => array(
						'_id' => array(
								'adId' => '$_id.adId',
								'fbId' => '$_id.fbId',
								'd'    => '$_id.d',
								'gn'   => '$goals.n',
								'gt'   => '$goals.t'
							),
						'gnb' => array('$sum'=>'$goals.nb'),
						'gna' => array('$sum'=>'$goals.a')
						)
					)
			);

		$goals = $coll->aggregate($ops);
	}

	/**
	 * split 6
	 */
	public function split6()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();		

		$start = new \DateTime();
		$start->setTimestamp(strtotime('1 september 2012'));

		$end   = new \DateTime();
		$end->setTimestamp(strtotime('12 february 2013'));

		$trs = SMDateInterval::getTimeRanges($start->format('Y-m-d'), $end->format('Y-m-d'),'Y-m-d');

		$days = [];
		foreach ($trs['days'] as $dayTR) {
			$days[] = (int) $dayTR->format('Ymd');
		}

		echo "<<<< SPLIT 6 AGGREG >>>> \n";
		$m    = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db   = $m->selectDB('bench');

		\MongoCursor::$timeout = 120000;

		$ins = [];
		for($i=1; $i<=2000; $i++) $ins[] = $i;

		$coll = $db->selectCollection("adSplitBucket");

		$trsArray = array(
			array('db.w' => array('$in' => $trs['weeks'])),
			array('db.m' => array('$in' => $trs['months'])),
			array('db.d' => array('$in' => $days))
		);

		$startm = microtime(true);
		$ops = array(
				array(
					'$match' => array(
						'adId' => array('$in'=>$ins),
						'$or'  => array(
								array('db.w' => array('$in' => $trs['weeks'])),
								array('db.m' => array('$in' => $trs['months'])),
								array('db.d' => array('$in' => $days))
							)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							),
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc'),
					)
				),
			);

		$stats = $coll->aggregate($ops);

		$endm = microtime(true) - $startm;
		echo "Agreg with TRS ".count($stats['result']).":: ". $endm . "s \n";

		$startm = microtime(true);
		$ops = array(
				array(
					'$match' => array(
						'adId' => array('$in'=>$ins),
						'db.d' => array('$gte'=>(int) $start->format('Ymd')),
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							),
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc'),
						)
					)
			);

		$stats2 = $coll->aggregate($ops);
		$endm = microtime(true) - $startm;
		echo "Agreg by db.d ".count($stats2['result'])."  :: ". $endm . "s \n";

		$findQuery1 = array(
						'adId' => array('$in'=>$ins),
						'$or'  => array(
								array('db.w' => array('$in' => $trs['weeks'])),
								array('db.m' => array('$in' => $trs['months'])),
								array('db.d' => array('$in' => $days))
							)
						);

		$findQuery2 = array(
						'adId' => array('$in'=>$ins),
						'db.d' => array('$gte'=>(int) $start->format('Ymd')),
					);

		$startm = microtime(true);
		$test = $coll->find($findQuery1) ;
		$endm2= microtime(true) - $startm;

		$startm = microtime(true);
		$test2 = $coll->find($findQuery2) ;
		$endm = microtime(true) - $startm;
		echo "find by db.d  :: ". $endm . "s \n";
		echo "find by trs  :: ". $endm2 . "s \n";
	}

	/**
	 * split 7
	 */
	public function split7()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$start = new \DateTime();
		$start->setTimestamp(strtotime('1 september 2012'));

		$end   = new \DateTime();
		$end->setTimestamp(strtotime('12 february 2013'));

		$trs = SMDateInterval::getTimeRanges($start->format('Y-m-d'), $end->format('Y-m-d'),'Y-m-d');

		$days = [];
		foreach ($trs['days'] as $dayTR) {
			$days[] = (int) $dayTR->format('Ymd');
		}

		echo "<<<< SPLIT 7 AGGREG >>>> \n";
		$m    = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db   = $m->selectDB('bench');

		\MongoCursor::$timeout = 120000;

		$ins = [];
		for($i=1; $i<=2000; $i++) $ins[] = $i;

		$coll  = $db->selectCollection("AdStat7Day");
		$coll2 = $db->selectCollection("AdStat7Week");
		$coll3 = $db->selectCollection("AdStat7Month");

		// aggreg days
		$startm = microtime(true);
		$ops = array(
				array(
					'$match' => array(
						'adId' => array('$in'=>$ins),
						'd'    => array('$in' => $days)
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							),
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc'),
					)
				),
			);
		$a_days = $coll->aggregate($ops);
		$endm = microtime(true) - $startm;

		// aggreg weeks
		$startm2 = microtime(true);
		$ops = array(
				array(
					'$match' => array(
						'adId' => array('$in'=>$ins),
						'w'    => array('$in' => $trs['weeks'])
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							),
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc'),
					)
				),
			);
		$a_weeks = $coll->aggregate($ops);
		$endm2 = microtime(true) - $startm2;

		// aggreg months
		$startm3 = microtime(true);
		$ops = array(
				array(
					'$match' => array(
						'adId' => array('$in'=>$ins),
						'm'    => array('$in' => $trs['months'])
						)
					),
				array(
					'$group' => array(
						'_id' => array(
							'adId' => '$adId',
							'fbId' => '$fbId',
							),
						'i' => array('$sum' => '$i'),
						'c' => array('$sum'=>'$c'),
						's' => array('$sum'=>'$s'),
						'sc' => array('$sum'=>'$sc'),
						'uc' => array('$sum'=>'$uc'),
						'suc'=> array('$sum'=>'$suc'),
					)
				),
			);
		$a_months = $coll->aggregate($ops);
		$endm3 = microtime(true) - $startm3;

		$endm4 = $endm + $endm2 + $endm3;

        echo "Agreg days  ". (count($a_days['result'])).  " :: ". $endm . "s \n";
		echo "Agreg Weeks ". (count($a_weeks['result'])). " :: ". $endm2 . "s \n";
		echo "Agreg Month ". (count($a_months['result'])). ":: ". $endm3 . "s \n";
		echo "Agreg Total :: ". $endm4 . "s \n";
	}
}
