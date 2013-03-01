<?php
namespace SM\PokeEmbedBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SM\BenchBundle\Command\BaseCommand;

class GenerateDataCommand extends BaseCommand 
{

	protected $description = '';
	protected $help = array(
			'nbAds'  => '',
			'nbStat' => '',
			'chunk'  => ''
		);

	protected $ads = array();

	/**
	 * Configure current command
	 */
	public function configure()
	{
		$this->setName('bench:embedPoke:generateData')
			->setDescription($this->description)
            ->addOption('nbAds',         null, InputOption::VALUE_OPTIONAL, $this->help['nbAds'],         5000)
            ->addOption('nbStat',        null, InputOption::VALUE_OPTIONAL, $this->help['nbStat'],        600)
            ->addOption('chunk',         null, InputOption::VALUE_OPTIONAL, $this->help['chunk'],         10);
	}

	/**
	 * Execute current command
	 * 
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{	
		$this->setInput($input);
		$this->setOutput($output);

		$output->writeln("****************************");
        $output->writeln("*");
        $output->writeln("* MASS INSERT :");
        $output->writeln("*");
        $output->writeln("*");
        $output->writeln("* nbAds : " .$input->getOption('nbAds'));
        $output->writeln("* nbStat : " .$input->getOption('nbStat'));
        $output->writeln("* chunk : " .$input->getOption('chunk'));
        $output->writeln("*");  
        $output->writeln("*");
        $output->writeln("****************************");

        $this->clearStats();
		$this->generateAds();
		//$this->insert();
	}


	protected function generateAds()
	{
		$this->getOutput()->writeln('Start Generating Ads');
		for ($i=1; $i<=$this->getInput()->getOption('nbAds'); $i++) {



			$date = new \MongoDate(strtotime('-'.$i.' days'));
			$mId  = floor($this->getInput()->getOption('nbAds')/500);

			$this->ads[] = array(
					'cAt'  => $date,
					'fId'  => $i+1,
					'isA'  => false,
					'isD'  => false,
					'mId'  => $mId,
					'n'    => 'AD '.$i,
					'st'   => rand(1,5),
					'sSt'  => rand(1,5),
					'dMS'  => $this->generateStats(),
					'dGS'  => $this->generateGoals()
				);

			if($i % $this->getInput()->getOption('chunk') === 0) {
				$this->insert($this->getInput()->getOption('chunk'), $i);
			}

			echo "COunt : " . count($this->ads)."\n";
		}

		if(count($this->ads) > 0) {
			$this->insert($this->getInput()->getOption('chunk'), $i);
		}
		$this->getOutput()->writeln('ENd Generating Ads');
	}

	protected function generateGoals()
	{
		$goals = array();

		for ($i=1; $i<=$this->getInput()->getOption('nbStat'); $i++) {
			$gnb = rand(1,1000);

			$goals[] = array(
					'gna' => $gnb * floatval('1.'+rand(0, 999)),
					'gnb' => $gnb,
					'gnt' => ($i%2==0) ? 'eng' : 'acq',
					'oid' => rand(1,5),
					'd'   => new \MongoDate(strtotime('-'.$i.' days')),
				);
		}

		return $goals;
	}

	protected function generateStats()
	{
		$stats = array();

		for ($i=1; $i<=$this->getInput()->getOption('nbStat'); $i++) {
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

			$stats[] = array(
					's'   => $spent,
					'c'   => $click,
					'sc'  => $socialClick,
					'suc' => $socialUClick,
					'i'   => $imp,
					'si'  => $socialImp,
					'd'   => new \MongoDate(strtotime('-'.$i.' days')),
					'sui' => $socialUImp,
					'cp'  => $impPayout/2,
					'nfi' => $nf_imp,
					'nfc' => $nf_click,
					'nfp' => $nf_pos
				);
		}

		return $stats;
	}

	/**
	 * Insert datas in DB
	 */
	protected function insert($chunk, $i)
	{
			$this->getOutput()->writeln('Chunk insert ['.$chunk . ':'. $i . ']');
				$coll = $this->getDocumentManager()->getDocumentCollection('SMPokeEmbedBundle:Ad');

            $coll->batchInsert($this->ads, array(
                    "w" => 0,
                    "j" => 0
                ));

            $this->ads = array();
		
	}

	/**
 	 * CLear Stats
 	 */
	public function clearStats()
    {
        $this->getOutput()->writeln('Cleaning collection');

        $coll = $this->getDocumentManager()->getDocumentCollection('SMPokeEmbedBundle:Ad');
        $coll->remove(array());
    }
}