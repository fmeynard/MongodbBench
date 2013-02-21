<?php 
namespace SM\GoalsAgregBundle\Command;

use SM\BenchBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class BaseReduceGoalsCommand extends BaseCommand
{
	protected $help = array(
			'generateStats' => '',
			'useAG'         => '',
			'useMR'         => '',
			'nbAds'         => '',
			'nbStat'        => '',
			'chunk'			=> ''
		);

	protected $description = '';

	/**
	 * Configure current command
	 *
	 * 
	 */
	public function configure()
	{
		$this->setName($this->getCmdName())
			->setDescription($this->description)
			->addOption('generateStats', null, InputOption::VALUE_OPTIONAL, $this->help['generateStats'], false)
			->addOption('useAG',         null, InputOption::VALUE_OPTIONAL, $this->help['useAG'],         false)
            ->addOption('useMR',         null, InputOption::VALUE_OPTIONAL, $this->help['useMR'],         false)
            ->addOption('nbAds',         null, InputOption::VALUE_OPTIONAL, $this->help['nbAds'],         500)
            ->addOption('nbStat',        null, InputOption::VALUE_OPTIONAL, $this->help['nbStat'],        500*600)
            ->addOption('chunk',         null, InputOption::VALUE_OPTIONAL, $this->help['chunk'],         1000);
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
        $output->writeln("* generateStats : " .($input->getOption('generateStats')) ? "yes" : "no");
        $output->writeln("*");
        $output->writeln("* useAG : " .($input->getOption('useAG')) ? "yes" : "no");
        $output->writeln("* useMR : " .($input->getOption('useMR')) ? "yes" : "no");
        $output->writeln("*");
        $output->writeln("* nbAds : " .$input->getOption('nbAds'));
        $output->writeln("* nbStat : " .$input->getOption('nbStat'));
        $output->writeln("*");  
        $output->writeln("*");
        $output->writeln("****************************");

		if ($input->getOption('generateStats')) {
			$this->generateStats();
		}

		$this->start($input, $output);
	}

	/**
	 * Start 
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function start(InputInterface $input, OutputInterface $output) 
	{

		if ($input->getOption('useAG')) {
			$this->executeAG($input, $output);
		} elseif($input->getOption('useMR')) { 
			$this->executeMR($input, $output);
		} else {
			$this->executePHP($input, $output);
		}
	}

	/**
	 * Execute current command with aggregate framework & native mongo driver
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	abstract protected function executeAG(InputInterface $input, OutputInterface $output);

	/**
	 * Execute current command with map/reduce 
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	abstract protected function executeMR(InputInterface $input, OutputInterface $output);

	/**
	 * Execute current command with map/reduce 
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	abstract protected function executePHP(InputInterface $input, OutputInterface $output);

	/**
	 * Generate stats for bench
	 */
	abstract protected function generateStats();

	/**
	 * Get command name 
	 *
	 * @return string
	 */
	abstract protected function getCmdName();

	/**
	 * Generate datas in collection "StatDayWithSplitGoals"
	 */
	protected function generateStatDayWithSplitGoals()
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
                    		array(
                    			'ccc' => 'fb|like|apple',
                    			'gna' => 2 * $i,
                    			'gnb' => $i
                    		),
                    		array(
                    			'ccc' => 'fb|like|microsoft',
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

        $output->writeln('Stat day Executed in ' .$end);
	}

	/**
	 * Generate StatDay with 
	 */
	public function generateSingleGoals()
	{
		$input  = $this->getInput();
		$output = $this->getOutput();

		$output->writeln('Cleaning collection');

		$coll = $this->getDocumentManager()->getDocumentCollection('SMGoalsAgregBundle:SingleGoal');
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
                    'gnb'  => rand(1,5),
                    'gna'  => rand(1,5) * rand(10,30),
                    'gnt'  => (rand(1,2) % 2) ? 'eng' : 'acq',
                    'obid' => rand(1,10)
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

        $output->writeln('Goals Executed in ' .$end);
	}
}