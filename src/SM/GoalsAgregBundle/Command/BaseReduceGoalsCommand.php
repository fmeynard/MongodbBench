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
            ->addOption('nbStat',        null, InputOption::VALUE_OPTIONAL, $this->help['nbStat'],        300000)
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
}