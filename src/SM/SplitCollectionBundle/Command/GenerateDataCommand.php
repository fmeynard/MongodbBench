<?php 
namespace SM\SplitCollectionBundle\Command;

use SM\BenchBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GenerateDataCommand extends BaseCommand
{
	protected $description ='';
	protected $adsMeta = array();

	protected $help = array(
			'nbAds'         => '',
			'nbStat'        => '',
			'chunk' => '',
			'splitAlt'     => '',
			'startNb' => ''
		);

	/**
	 * Configure current command
	 *5.135.9.59
	 * 
	 */
	public function configure()
	{
		$this->setName('bench:split:generateData')
			->setDescription($this->description)
            ->addOption('nbAds',         null, InputOption::VALUE_OPTIONAL, $this->help['nbAds'],         200)
            ->addOption('nbStat',        null, InputOption::VALUE_OPTIONAL, $this->help['nbStat'],        180)
            ->addOption('chunk',         null, InputOption::VALUE_OPTIONAL, $this->help['chunk'],         1000)
            ->addOption('startNb',       null, InputOption::VALUE_OPTIONAL, $this->help['startNb'],       1)
            ->addOption('splitAlt',      null, InputOption::VALUE_OPTIONAL, $this->help['splitAlt'])
            ->addOption('splitAltOld',   null, InputOption::VALUE_OPTIONAL, $this->help['splitAlt'])
            ->addOption('split6',        null, InputOption::VALUE_OPTIONAL, $this->help['splitAlt']);
	}

	/**
	 * Execute current command : clear db if necessary and forward to specified action
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
        $output->writeln("* nbAds : " .$input->getOption('nbAds'));
        $output->writeln("* nbStat : " .$input->getOption('nbStat'));
        $output->writeln("*");  
        $output->writeln("*");
        $output->writeln("****************************");

        //ggc_enable();
        if ($input->getOption('split6')) {
        	$this->generateSplit6();
        } elseif ($input->getOption('splitAlt')) {
        	$this->goalsByObjectId($input, $output);
        } elseif ($input->getOption('splitAlt')) {
        	$this->goalsByObjectIdOld($input, $output);
        } else {

			$this->generateDayStats($input, $output);
			$this->generateGoals($input, $output);
		}

		//gc_disable();
	}

	/**
	 * Generate datas in collection "StatDayWithSplitGoals"
	 */
	protected function generateDayStats(InputInterface $input, OutputInterface $output)
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$output->writeln('Cleaning collection');

        //$coll = $this->getDocumentManager()->getDocumentCollection('SMSplitCollectionBundle:AdStat');
        $m = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db = $m->selectDB('bench');
        $coll = $db->selectCollection('adstatsplit');
        $coll->remove(array()); 

        $nbStat = $input->getOption('nbStat');
        $chunk  = $input->getOption('chunk');
        $nbAds  = $input->getOption('nbAds');

        $start = microtime(true);
        $currents = [];

        for($x=1;$x<=$nbAds;$x++)
        {
	        for($i=1;$i<=$nbStat;$i++)
	        {
	        	$fbId = $x;
	        	$adId = $x;
	        	$date = new \MongoDate(strtotime("- $i days"));

	        	$imp          = rand(1, 500000);
				$uniqueImp    = rand(1, $imp);
				$socialImp    = rand(1, $uniqueImp);
				$socialUImp   = rand(1, $socialImp);
				$click        = rand(1, $imp);
				$uniqueClick  = rand(1, $click);
				$socialClick  = rand(1, $socialImp);
				$socialUClick = rand(1, $socialClick);
				$nf_imp       = rand(1, $imp);
				$nf_click     = rand(1, $nf_imp);
				$nf_pos       = rand(1, 15);
				$impPayout    = floatval('1.'+rand(0, 999));
				$spent        = $impPayout * ceil($imp / 1000);

	        	if(!isset($this->adsMeta[$x])) {
	        		$this->adsMeta[$x] = $input->getOption('nbAds')/100;
	        	}

	            $current = array(
	            		'_id' => array(
	            			'fbId' => $fbId,
	            			'd'    => $date
	            			),
	            		'adId' => $adId,
	                    'metaId' => $this->adsMeta[$x],
	                    's'   => $spent,
						'c'   => $click,
						'sc'  => $socialClick,
						'suc' => $socialUClick,
						'i'   => $imp,
						'si'  => $socialImp,
						'sui' => $socialUImp,
						'cp'  => $impPayout/2,
						'nfi' => $nf_imp,
						'nfc' => $nf_click,
						'nfp' => $nf_pos
	                );

	            $currents[] = $current;

	            if (count($currents) >= $chunk) {
	                $this->batchInsert($currents, $coll);
	                unset($currents);
	                $currents = [];
	            }

	            unset($fbId, $adId, $date, $imp, $uniqueImp, $socialUImp, 
	            	$socialImp, $click, $uniqueClick, $socialClick,
	            	$nf_imp, $nf_pos, $impPayout, $spent, $current);
	        }


        	echo 'Ad : ' . $x . " Done \n";
    	}

        if (count($currents) > 0) {
            $this->batchInsert($currents, $coll);
        }

        $end = microtime(true) - $start;

        echo 'Stat day Executed in ' .$end;
	}

	/**
	 * Generate StatDay with 
	 */
	public function generateGoals(InputInterface $input, OutputInterface $output)
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$nbStat = $input->getOption('nbStat');
        $chunk  = $input->getOption('chunk');
        $nbAds  = $input->getOption('nbAds');

		$output->writeln('Cleaning collection');

		$m = new \MongoClient('mongodb://' . $input->getOption('host'));
        $db = $m->selectDB('bench');
        $coll = $db->selectCollection('goalstatsplit');
		$coll->remove(array());

		$start = microtime(true);
		$currents = [];

        for($x=1; $x<=$nbAds; $x++)
        {
        	echo "Ad : $x";
	        for($i=1;$i<=$nbStat;$i++)
	        {
	        	$rand=rand(1,20);
	        	for($z=1;$z<=$rand;$z++)
	        	{
	        		$output->writeln("Goal $z : $rand");
		        	$fbId = $x;
		        	$adId = $x;
		        	$date = new \MongoDate(strtotime("- $i days"));

		        	if(!isset($this->adsMeta[$x])) {
		        		$this->adsMeta[$x] = $nbAds/100;
		        	}

		            $current = array(
		            		'_id' => array(
		            			'fbId' => $fbId,
		            			'd'    => $date,
			                    'type'   => $z,
			                    'obid'   => rand(1,$z)
		            			),
		                    'adId'   => $adId,
		                    'metaId' => $this->adsMeta[$x],
		                    'gnb'    => rand(1,5),
		                    'gna'    => rand(1,5) * rand(10,30),
		                    'gnt'    => (rand(1,2) % 2) ? 'eng' : 'acq',
		                );

		            $currents[] = $current;

		            if (count($currents) >= $chunk) {
		                $this->batchInsert($currents, $coll);
		                unset($currents);
		                $currents = [];
		            }
		        }
	        }
	    }

	    if (count($currents) > 0) {
	        $this->batchInsert($currents, $coll);
	    }

        $end = microtime(true) - $start;

        $output->writeln('Goals Executed in ' .$end);
	}

	protected function goalsByObjectId(InputInterface $input, OutputInterface $output)
	{
		$gv = array(
			'photo_view', 
			'like', 
			'app_install', 
			'app_use', 
			'comment', 
			'music_listen', 
			'geek', 
			'share', 
			'post_click',
			'diablo3',
			'pokemon',
			'apple_love',
			'offer_buy',
			'video_view',
			'video_share',
			'retweet'
			);

		$acq = array(
			'photo_view', 
			'like',
			'post_click',
			'diablo3',
			'retweet',
			'comment' 
			);

		$input  = $this->getInput();
		$output = $this->getOutput();

		$nbStat = $input->getOption('nbStat');
        $chunk  = $input->getOption('chunk');
        $nbAds  = $input->getOption('nbAds');

		$output->writeln('Cleaning collection');

		$m = new \MongoClient('mongodb://' . $input->getOption('host'));
        $db = $m->selectDB('bench');
        $coll = $db->selectCollection('goalstatsplit4');
		$coll->remove(array());

		$coll2 = $db->selectCollection('goalstatsplit5');
		$coll2->remove(array());

		$start = microtime(true);
		$currents = [];

		for ($x=1; $x<=$nbAds; $x++) {
			echo "Ad $x \n";

			$nbObjects = rand(1,5);
			$nbGoals   = rand(1,10);

			for ($i=1; $i<=$nbStat; $i++) {
				
				$fbId = $x;
		        $adId = $x;
		        $date = new \MongoDate(strtotime("- $i days"));

		        $current2 = array(
							'_id'   => new \MongoId(),
							'adId'  => $adId,
							'fbId'  => $fbId,
							'd'     => $date,
						);

				$goals = [];

				for($w=1; $w<=$nbObjects; $w++) {
					for ($z=1; $z<=$nbGoals; $z++) {
						$nb = rand(1,50);

						$goals[] =  array(
								'n'  => $gv[$z],
								'a'  => $nb * rand(1,5),
								'nb' => $nb,
								't'  => (in_array($gv[$z], $acq)) ? 'acq' : 'eng',
								'o'  => $w
							);
					}

					$current = array(
							'_id'   => new \MongoId(),
							'adId'  => $adId,
							'fbId'  => $fbId,
							'oid'   => $w,
							'd'     => $date,
							'goals' => $goals,
						);

					$currents[] = $current;

					if (count($currents) > $chunk) {
						$this->batchInsert($currents, $coll);
						unset($currents); 
						$currents = [];
					}
				}

				$current2['goals'] = $goals;

				$currents2[] = $current2;

				if (count($currents2) > $chunk) {
					$this->batchInsert($currents2, $coll2);
					unset($currents2); 
					$currents2 = [];
				}
			}
		}

		if (count($currents) > 0) {
			$this->batchInsert($currents, $coll);
		}

		if (count($currents2) > 0) {
			$this->batchInsert($currents2, $coll2);
		}
	}

	protected function goalsByObjectIdOld(InputInterface $input, OutputInterface $output)
	{
		$gv = array(
			'photo_view', 
			'like', 
			'app_install', 
			'app_use', 
			'comment', 
			'music_listen', 
			'geek', 
			'share', 
			'post_click',
			'diablo3',
			'pokemon',
			'apple_love',
			'offer_buy',
			'video_view',
			'video_share',
			'retweet'
			);

		$input  = $this->getInput();
		$output = $this->getOutput();

		$nbStat = $input->getOption('nbStat');
        $chunk  = $input->getOption('chunk');
        $nbAds  = $input->getOption('nbAds');

		$output->writeln('Cleaning collection');

		$m = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db = $m->selectDB('bench');
        $coll = $db->selectCollection('goalstatsplit2');
		$coll->remove(array());

		$coll2 = $db->selectCollection('goalstatsplit3');
		$coll2->remove(array());

		$start = microtime(true);
		$currents = [];

		for ($x=1; $x<=$nbAds; $x++) {
			echo "Ad $x \n";

			$nbObjects = rand(1,5);
			$nbGoals   = rand(1,10);

			for ($i=1; $i<=$nbStat; $i++) {
				
				$fbId = $x;
		        $adId = $x;
		        $date = new \MongoDate(strtotime("- $i days"));

		        $current2 = array(
							'_id'   => new \MongoId(),
							'adId'  => $adId,
							'fbId'  => $fbId,
							'd'     => $date,
						);

		        $objects = [];

				for($w=1; $w<=$nbObjects; $w++) {
					$goals = [];
					for ($z=1; $z<=$nbGoals; $z++) {

						if(!isset($goals[$gv[$z]])) {
							$goals[$gv[$z]] = array(
								'a'=>0,
								'nb'=>0
								);
						}

						$nb = rand(1,50);

						$goals[$gv[$z]]['a']  += $nb * rand(1,5);
						$goals[$gv[$z]]['nb'] += $nb;

					}

					$current = array(
							'_id'   => new \MongoId(),
							'adId'  => $adId,
							'fbId'  => $fbId,
							'oid'   => $w,
							'd'     => $date,
							'goals' => $goals,
						);

					$currents[] = $current;

					if (count($currents) > $chunk) {
						$this->batchInsert($currents, $coll);
						unset($currents); 
						$currents = [];
					}

					$objects[$w] = $goals;
				}

				$current2['objects'] = $objects;

				$currents2[] = $current2;

				if (count($currents2) > $chunk) {
					$this->batchInsert($currents2, $coll2);
					unset($currents2); 
					$currents2 = [];
				}
			}
		}

		if (count($currents) > 0) {
			$this->batchInsert($currents, $coll);
		}

		if (count($currents2) > 0) {
			$this->batchInsert($currents2, $coll2);
		}
	}

	protected function generateSplit6()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$nbStat  = $input->getOption('nbStat');
        $chunk   = $input->getOption('chunk');
        $nbAds   = $input->getOption('nbAds');
        $startNb = $input->getOption('startNb');

		$m = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db = $m->selectDB('bench');
        $coll = $db->selectCollection('adSplitBucket');

		$start = microtime(true);
		$adStats    = [];
		$weekStats  = [];
		$monthStats = [];

		$last_week  = null;
		$last_month = null;
		$aWeek      = null;
		$aMonth     = null;

		$adsMeta    = [];

		for ($i=$startNb; $i<=($nbAds+$startNb);$i++) {
			for ($x=1; $x<=$nbStat; $x++) {
				$fbId = $i;
		        $adId = $i;
		        $time = strtotime("- $x days");
		        $date = new \MongoDate($time);

		        $imp          = rand(1, 500000);
				$uniqueImp    = rand(1, $imp);
				$socialImp    = rand(1, $uniqueImp);
				$socialUImp   = rand(1, $socialImp);
				$click        = rand(1, $imp);
				$uniqueClick  = rand(1, $click);
				$socialClick  = rand(1, $socialImp);
				$socialUClick = rand(1, $socialClick);
				$nf_imp       = rand(1, $imp);
				$nf_click     = rand(1, $nf_imp);
				$nf_pos       = rand(1, 15);
				$impPayout    = floatval('1.'+rand(0, 999));
				$spent        = $impPayout * ceil($imp / 1000);

	        	if(!isset($this->adsMeta[$i])) {
	        		$this->adsMeta[$i] = $input->getOption('nbAds')/100;
	        	}

	            $adStat = array(
	            		'fbId' => $fbId,
	            		'adId' => $adId,
	                    'metaId' => $this->adsMeta[$i],
	                    's'   => $spent,
						'c'   => $click,
						'sc'  => $socialClick,
						'suc' => $socialUClick,
						'i'   => $imp,
						'si'  => $socialImp,
						'sui' => $socialUImp,
						'cp'  => $impPayout/2,
						'nfi' => $nf_imp,
						'nfc' => $nf_click,
						'nfp' => $nf_pos,
						'db' => array(
							'd' => (int) date('Ymd', $time),
							)
	                );

	            $coll->insert($adStat);
	            
	            $coll->update(
	            	array(
	            		'fbId'   => $fbId, 
	            		'adId'   => $adId, 
	            		'metaId' => $this->adsMeta[$i],
	            		'db.w'   => date('Y', $time).'-'.date('W', $time)
	            		),
	            	array(
	            		'$inc' => array(
	            			's'   => $spent,
							'c'   => $click,
							'sc'  => $socialClick,
							'suc' => $socialUClick,
							'i'   => $imp,
							'si'  => $socialImp,
							'sui' => $socialUImp,
							'cp'  => $impPayout/2,
							'nfi' => $nf_imp,
							'nfc' => $nf_click,
							'nfp' => $nf_pos,
	            			),
	            		),
	            	array('upsert'=>true)
	            	);

	            $coll->update(
	            	array(
	            		'fbId'   => $fbId, 
	            		'adId'   => $adId,
	            		'metaId' => $this->adsMeta[$i],
	            		'db.m'   => date('Y', $time).'-'.date('m', $time)
	            		),
	            	array(
	            		'$inc' => array(
	            			's'   => $spent,
							'c'   => $click,
							'sc'  => $socialClick,
							'suc' => $socialUClick,
							'i'   => $imp,
							'si'  => $socialImp,
							'sui' => $socialUImp,
							'cp'  => $impPayout/2,
							'nfi' => $nf_imp,
							'nfc' => $nf_click,
							'nfp' => $nf_pos,
	            			),
	            		),
	            	array('upsert'=>true)
	            	);

			}
		}
	}

	public function split7()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$nbStat  = $input->getOption('nbStat');
        $chunk   = $input->getOption('chunk');
        $nbAds   = $input->getOption('nbAds');
        $startNb = $input->getOption('startNb');

		$m = new \MongoClient('mongodb://'.$input->getOption('host'));
        $db = $m->selectDB('bench');
        $coll = $db->selectCollection('AdStat7day');
        $coll2 = $db->selectCollection('AdStat7Week');
        $coll3 = $db->selectCollection('AdStat7Month');

		$start = microtime(true);
		$adStats    = [];
		$weekStats  = [];
		$monthStats = [];

		$last_week  = null;
		$last_month = null;
		$aWeek      = null;
		$aMonth     = null;

		$adsMeta    = [];

		for ($i=$startNb; $i<=($nbAds+$startNb);$i++) {
			for ($x=1; $x<=$nbStat; $x++) {
				$fbId = $i;
		        $adId = $i;
		        $time = strtotime("- $x days");
		        $date = new \MongoDate($time);

		        $imp          = rand(1, 500000);
				$uniqueImp    = rand(1, $imp);
				$socialImp    = rand(1, $uniqueImp);
				$socialUImp   = rand(1, $socialImp);
				$click        = rand(1, $imp);
				$uniqueClick  = rand(1, $click);
				$socialClick  = rand(1, $socialImp);
				$socialUClick = rand(1, $socialClick);
				$nf_imp       = rand(1, $imp);
				$nf_click     = rand(1, $nf_imp);
				$nf_pos       = rand(1, 15);
				$impPayout    = floatval('1.'+rand(0, 999));
				$spent        = $impPayout * ceil($imp / 1000);

	        	if(!isset($this->adsMeta[$i])) {
	        		$this->adsMeta[$i] = $input->getOption('nbAds')/100;
	        	}

	            $adStat = array(
	            		'fbId' => $fbId,
	            		'adId' => $adId,
	                    'metaId' => $this->adsMeta[$i],
	                    's'   => $spent,
						'c'   => $click,
						'sc'  => $socialClick,
						'suc' => $socialUClick,
						'i'   => $imp,
						'si'  => $socialImp,
						'sui' => $socialUImp,
						'cp'  => $impPayout/2,
						'nfi' => $nf_imp,
						'nfc' => $nf_click,
						'nfp' => $nf_pos,
						'd'   => (int) date('Ymd', $time),
	                );

	            $coll->insert($adStat);
	            
	            $coll2->update(
	            	array(
	            		'fbId'   => $fbId, 
	            		'adId'   => $adId, 
	            		'metaId' => $this->adsMeta[$i],
	            		'w'   => date('Y', $time).'-'.date('W', $time)
	            		),
	            	array(
	            		'$inc' => array(
	            			's'   => $spent,
							'c'   => $click,
							'sc'  => $socialClick,
							'suc' => $socialUClick,
							'i'   => $imp,
							'si'  => $socialImp,
							'sui' => $socialUImp,
							'cp'  => $impPayout/2,
							'nfi' => $nf_imp,
							'nfc' => $nf_click,
							'nfp' => $nf_pos,
	            			),
	            		),
	            	array('upsert'=>true)
	            	);

	            $coll3->update(
	            	array(
	            		'fbId'   => $fbId, 
	            		'adId'   => $adId,
	            		'metaId' => $this->adsMeta[$i],
	            		'm'   => date('Y', $time).'-'.date('m', $time)
	            		),
	            	array(
	            		'$inc' => array(
	            			's'   => $spent,
							'c'   => $click,
							'sc'  => $socialClick,
							'suc' => $socialUClick,
							'i'   => $imp,
							'si'  => $socialImp,
							'sui' => $socialUImp,
							'cp'  => $impPayout/2,
							'nfi' => $nf_imp,
							'nfc' => $nf_click,
							'nfp' => $nf_pos,
	            			),
	            		),
	            	array('upsert'=>true)
	            	);

			}
		}
	}

	protected function batchInsert(&$currents, &$coll)
	{
		$coll->batchInsert($currents, array(
                    "w" => 0,
                    "j" => 0
                ));
	}
}