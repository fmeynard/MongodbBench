<?php
namespace SM\FlatAdStatBundle\Command;

use SM\BenchBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use SM\BenchBundle\Document\StatDay;

class GenerateAdStatFlatCommand extends BaseCommand
{
    protected $help = array(
        'nbAds'  => 'AdIds count number to generate',
        'nbStat' => 'Stats number',
        'useODM' => 'useODM or DBAL',
        'useQB'  => 'useQB',
        'chunk'  => 'chunk size'
    );

    protected $description = 'Mass Inserts';

    /**
     * Configure current command
     */
    public function configure()
    {

        $this->setName('bench:adStatFlat:generate')
            ->setDescription($this->description)
            ->addOption('nbAds', null,  InputOption::VALUE_OPTIONAL, $this->help['nbAds'], 500)
            ->addOption('nbStat', null, InputOption::VALUE_OPTIONAL, $this->help['nbStat'], 300000)
            ->addOption('useODM', null, InputOption::VALUE_NONE, $this->help['useODM'])
            ->addOption('useQB',  null, InputOption::VALUE_NONE, $this->help['useQB'])
            ->addOption('chunk', null, InputOption::VALUE_OPTIONAL, $this->help['chunk'],1000);
    }

    /**
     * Execute current command ( split by useODM parameter )
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $ouput
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInput($input);
        $this->setOutput($output);

        $output->writeln("****************************");
        $output->writeln("*");
        $output->writeln("* MASS INSERT :");
        $output->writeln("*");
        $output->writeln("* UseODM : " .($input->getOption('useODM')) ? "yes" : "no");
        $output->writeln("* useQB : " .($input->getOption('useQB')) ? "yes" : "no");
        $output->writeln("*");
        $output->writeln("* nbAds : " .$input->getOption('nbAds'));
        $output->writeln("* nbStat : " .$input->getOption('nbStat'));
        $output->writeln("* chunk : " .$input->getOption('chunk'));
        $output->writeln("*");  
        $output->writeln("*");
        $output->writeln("****************************");

        if ($input->getOption('useODM')) {

            $this->executeWithODM($input, $output);
        } elseif ($input->getOption('useQB')) {
            $this->executeWithQB($input, $output);
        } else {

            $this->executeWithoutODM($input, $output);
        }
    }

    /**
     * Execute current command with ODM
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function executeWithODM(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("MassCreate : use MongoODM");
        $this->clearStats();

        $dm = $this->getDocumentManager();


        $start = microtime(true);

        for($i=1;$i<$input->getOption('nbStat');$i++)
        {
            $current = new StatDay();
            $current->setAdId(rand(1, $input->getOption('nbAds')));
            $current->setMetaId(rand(1, $input->getOption('nbAds')/100));
            $current->setClick(10+$i);
            $current->setSpent(20+$i);
            $current->setSocialClick(30+$i);
            $current->setUniqueClick(40+$i);
            $current->setSocialUniqueClick(50+$i);
            $current->setImpression(60+$i);

            $dm->persist($current);

            if ($i % $input->getOption('chunk') == 0) {
                $dm->flush();
            }
        }
        
        $dm->flush();

        $end = microtime(true) - $start;

        $output->writeln('Executed in ' .$end);
    }

    /**
     * Execute current command with ODM 
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function executeWithoutODM(InputInterface $input, OutputInterface $output) 
    {
        $output->writeln("MassCreate : use mongo connection directly");
        $this->clearStats();

        $start = microtime(true);

        $coll = $this->getDocumentManager()->getDocumentCollection('SMBenchBundle:StatDay');

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
                    'i' => 60 + $i
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

    /**
     * Execute current command using query builder
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function executeWithQB(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("MassCreate : use query builder");
        $this->clearStats();

        $dm = $this->getDocumentManager();

        for ($i=1;$i<=$input->getOption('nbStat');$i++) {
            $dm->createQueryBuilder('SMBenchBundle:StatDay')
                ->insert()
                ->field("adId")->set(rand(1, $input->getOption('nbAds')))
                ->field("metaId")->set(rand(1, $input->getOption('nbAds')/100))
                ->field("click")->set(10+$i)
                ->field("spent")->set(20+$i)
                ->field("socialClick")->set(30+$i)
                ->field("uniqueClick")->set(40+$i)
                ->field("socialUniqueClick")->set(50+$i)
                ->field('impression')->set(60+$i)
                ->getQuery()
                ->execute();
        }
    }

    public function clearStats()
    {
        $this->getOutput()->writeln('Cleaning collection');

        $coll = $this->getDocumentManager()->getDocumentCollection('SM\BenchBundle\Document\StatDay');
        $coll->remove(array());
    }
}