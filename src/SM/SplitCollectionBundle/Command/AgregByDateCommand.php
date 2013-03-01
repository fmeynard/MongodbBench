<?php 
namespace SM\SplitCollectionBundle\Command;


use SM\BenchBundle\Command\baseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AgregByDateCommand extends BaseCommand
{
	protected $description = '';
	protected $help = array(
			'ad' => '',
			'meta' => '',
			'start' => '',
			'end'   => '',
			'split' => ''
		);

	public function configure()
	{
		$this->setName('bench:split:reduceTime')
			->setDescription($this->description)
			->addOption('ad',    null, InputOption::VALUE_OPTIONAL, $this->help['ad'])
			->addOption('meta',  null, InputOption::VALUE_OPTIONAL, $this->help['meta'])
			->addOption('start', null, InputOption::VALUE_OPTIONAL, $this->help['start'])
			->addOption('end',   null, InputOption::VALUE_OPTIONAL, $this->help['end'])
			->addOption('split2', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split3', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split4', null, InputOption::VALUE_OPTIONAL, $this->help['split'])
			->addOption('split5', null, InputOption::VALUE_OPTIONAL, $this->help['split']);

	}	

	/**
	 * Execute current command
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
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
		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));

		echo "Agreg By Meta/Date";
		$m    = new \MongoClient('mongodb://5.135.9.59');
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

	protected function split2()
	{
		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));


		$ins = [];
		for($i=1; $i<500; $i++) $i[] = $ins;

		echo "Agreg By Meta/Date";
		$m    = new \MongoClient('mongodb://5.135.9.59');
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

	protected function split3()
	{
		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));

		echo "<<<< SPLIT 3 AGGREG >>>> \n";
		$m    = new \MongoClient('mongodb://5.135.9.59');
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

	protected function split5()
	{
		$start = new \MongoDate(strtotime('-60 days'));
		$end   = new \MongoDate(strtotime('now'));

		echo "<<<< SPLIT 5 AGGREG >>>> \n";
		$m    = new \MongoClient('mongodb://5.135.9.59');
        $db   = $m->selectDB('bench');

		\MongoCursor::$timeout = 120000;

		$ins = [];
		for($i=1; $i<2; $i++) $ins[] = $i;

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
							'd'    => '$d'
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

		print_r($goals); 
	}
}