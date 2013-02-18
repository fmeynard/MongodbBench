<?php
namespace SM\FlatAdStatBundle\Command;

use SM\BenchBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateAdStatFlatCommand extends BaseCommand
{
    protected $help = array(
        'nbAds'  => 'AdIds count number to generate',
        'nbStat' => 'Stats number',
        'useODM' => 'useODM or DBAL',
        'useQB'  => 'useQB'
    );

    protected $description = 'Mass Inserts';

    /**
     * Configure current command
     */
    public function configure()
    {

        $this->setName('bench:adStatFlat:generate')
            ->setDescription($this->description)
            ->addOption('nbAds', null,  InputOption::VALUE_NONE, $this->help['nbAds'])
            ->addOption('nbStat', null, InputOption::VALUE_NONE, $this->help['nbStat'])
            ->addOption('useODM', null, InputOption::VALUE_NONE, $this->help['useODM'])
            ->addOption('useQB',  null, InputOption::VALUE_NONE, $this->help['useQB']);
    }

    /**
     * Execute current command ( split by useODM parameter )
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $ouput
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        if ($input->getOption('useODM')) {

            $this->executeWithODM($input, $output);
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

        //
    }

    /**
     * Execute current command with ODM 
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function executeWithoutODM(InputInterface $input, OutputInterface $output) 
    {

    }

    /**
     * Execute current command using query builder
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function executeWithQB(InputInterface $input, OutputInterface $output)
    {

    }
}