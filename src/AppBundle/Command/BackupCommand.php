<?php

namespace AppBundle\Command;

use BackupManager\Filesystems\Destination;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BackupCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:backup')
            // the short description shown while running "php bin/console list"
            ->setDescription('Backup all')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to backup things');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new \DateTime();

        $destinationList = [
            new Destination('local', 'backup'.$date->format('Y-m').'/'.$date->format('d_H-i-s').'.sql')
        ];

        $backup = $this->getContainer()
            ->get('backup_manager')
            ->makeBackup();

        $backup->run('production', $destinationList, 'gzip');

    }
}