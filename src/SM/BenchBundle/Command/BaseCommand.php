<?php
namespace SM\BenchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

abstract class BaseCommand extends ContainerAwareCommand
{

    protected $_ouput;
    protected $_input;
    protected $_dms    = array();

    /**
     * Getter : Document Manager 
     *
     * @param string $connection
     *
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager($connection = 'default')
    {

        if (true == empty($connection)) {

            $connection = "default";
        }

        if (false == isset($this->_dms[$connection])) {

            $this->_dms[$connection] =
            $this->get('doctrine_mongodb.odm'.$connection.'_document_manager');
        }

        return $this->_dms[$connection];
    }

    /**
     * Getter : AppKernel
     *
     * @return AppKernel
     */
    protected function getKernel()
    {

        return $this->getKernel();
    }

    /**
     * Setter : input interface
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    protected function setInput(InputInterface $input)
    {

        $this->_input = $input;
    }

    /**
     * Getter : input interface
     *
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    protected function getInput()
    {

        return $this->_input;
    }

    /**
     * Setter output
     *
     * @param \Symfony\Component\Console\Output\OutputInterface
     */
    protected function setOuput(OutputInterface $output)
    {

        $this->_output = $output;
    }

    /**
     * Getter output 
     *
     * @param \Symfony\Component\Console\Output\OutputInterface
     */
    protected function getOutput()
    {

        return $this->_output;
    }

    /**
     * Get service with given name
     *
     * @param string $serviceName
     *
     * @return mixed
     */
    protected function get($serviceName)
    {

        return $this->getContainer()->get($serviceName);
    }
}
