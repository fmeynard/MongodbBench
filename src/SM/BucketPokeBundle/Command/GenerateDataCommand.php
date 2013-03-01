<?php
namespace SM\BucketPokeBundle\Command;

use SM\BenchBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GenerateDataCommand extends BaseCommand
{
	protected $description = 'Generate datas for current poke';
	protected $help = array(
			'nbAds' => '',
			'nbStat' => '',
			'chunk' => ''
		);

	/**
	 * Configure current command
	 */
	public function configure()
	{
		$this->setName('bench:bucket:generateData')
			->setDescription($this->description)
            ->addOption('nbAds',         null, InputOption::VALUE_OPTIONAL, $this->help['nbAds'],         1)
            ->addOption('nbStat',        null, InputOption::VALUE_OPTIONAL, $this->help['nbStat'],        600)
            ->addOption('chunk',         null, InputOption::VALUE_OPTIONAL, $this->help['chunk'],         1000);
	}

	/**
	 * Executes current command
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$this->setInput($input);
		$this->setOutput($output);

		$this->clearStats();
		$this->generate();
	}

	/**
	 * Generate datas
	 */
	public function generate()
	{
		$nbAds  = $this->getInput()->getOption('nbAds');
		$nbStat = $this->getInput()->getOption('nbStat');

		for ($i=0; $i<$nbAds; $i++)
		{
			for($x=0;$x<$nbStat;$x++)
			{
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
				$time         = strtotime('-'.$x.' days');
				$fId          = rand(1, $this->getInput()->getOption('nbAds'));

				$base = array(
						'fId' => $fId,
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

				$newData = array(
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
							'nfp' => $nf_pos
							)
					);

				$coll->insert(
						array('fId'=>$fId),
						$newData,
						array('upsert'=>true)	
					);

				$bucketDateD = array(
						'db' => array(
							'd' => date('Y-m-d', $time),
							)
					);

				$bucketDateM = array(
						'db' => array(
							'm' => date('Y-m', $time),
							)
					);

				$bucketDateW = array(
						'db' => array(
							'w' => date('Y-W', $time),
						)
					);

				$bucketDate = array('db' => array());

				$

				$this->datas[] = array_merge($base, $bucketDateD);
				$this->datas[] = array_merge($base, $bucketDateW);
				$this->datas[] = array_merge($base, $bucketDateM);
				$this->datas[] = array_merge($base, $bucketDate);
			}

			if (count($this->datas) > $this->getInput()->getOption('chunk')) {
				$this->processInsert();
			}
		}

		if (count($this->datas) > 0) { 
			$this->processInsert();
		}
	}

	/**
	 * Process insert
	 */
	protected function processInsert()
	{
		$coll = $this->getDocumentManager()->getDocumentCollection('SMBucketPokeBundle:StatBucket');

        $coll->batchInsert($this->datas, array(
            "w" => 0,
            "j" => 0
            ));

        $this->datas = array();
	}

	/**
 	 * CLear Stats
 	 */
	public function clearStats()
    {
        $this->getOutput()->writeln('Cleaning collection');

        $coll = $this->getDocumentManager()->getDocumentCollection('SMBucketPokeBundle:StatBucket');
        $coll->remove(array());
    }
}