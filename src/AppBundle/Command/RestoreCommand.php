<?php

namespace AppBundle\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RestoreCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('app:restore')
            ->addArgument('name', InputArgument::REQUIRED, 'file name to restore')
            ->setDescription('restore all')
            ->setHelp('This command allows you to restore things');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if(!$name){
            return 1;
        }

        $restore = $this->getContainer()->get('backup_manager')->makeRestore();
        $restore->run('dropbox', $name, 'production', 'gzip');


    }
}