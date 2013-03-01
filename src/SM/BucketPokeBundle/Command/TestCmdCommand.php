<?php
namespace SM\BucketPokeBundle\Command;

use SM\BenchBundle\Command\baseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCmdCommand extends BaseCommand 
{
	protected $description = '';
	protected $help = array(
		'nbAds' => 'plop'
		);

	public function configure()
	{
		$this->setName('bench:bucket:commands')
			->setDescription($this->description)
			->addOption('nbAds', null, InputOption::VALUE_OPTIONAL, $this->help['nbAds'], 5000);
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
	}

	protected function aggregateMonth()
	{

	}

	protected function aggregateWeek()
	{

	}

	protected function aggregateMultiple()
	{

	}
}