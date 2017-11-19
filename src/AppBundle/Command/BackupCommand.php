<?php

namespace AppBundle\Command;

use AppBundle\Component\Helper\DirectoryHelper;
use BackupManager\Filesystems\Destination;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see http://flysystem.thephpleague.com/api/#general-usage
 *
 * Class BackupCommand
 * @package AppBundle\Command
 */
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

        $fileFolder = 'backup' . $date->format('Y-m') . '/';
        $filename = $fileFolder . $date->format('d_H-i-s') . '.sql';
        $uploadDir = $this->getContainer()->getParameter('app.upload_dir');

        $localFileName = $this->getContainer()->getParameter('app.backup_dir') . $filename . '.gz';

        /**
         * first backup database to local.
         */
        $destinationList = [
            new Destination('local', $filename)
        ];
        $backup = $this->getContainer()
            ->get('backup_manager')
            ->makeBackup();
        $backup->run('production', $destinationList, 'gzip');


        /**
         * then upload mysql dump
         */
        $filesystem = $this->getContainer()->get('oneup_flysystem.dropbox_filesystem');
        $stream = fopen($localFileName, 'r+');
        $filesystem->writeStream($filename . '.gz', $stream);

        if (\is_resource($stream)) {
            fclose($stream);
        }

        /** finaly backup image */

        foreach ( DirectoryHelper::glob_recursive($uploadDir . '*') as $uploadedFile) {
            if(!is_file($uploadedFile)){
                continue;
            }

            $dropboxFile = $fileFolder . substr($uploadedFile, strlen($uploadDir));
            if(!$filesystem->has($dropboxFile)){

                /** upload only file that not exists this month */
                $stream = fopen($uploadedFile, 'r+');
                $filesystem->writeStream($dropboxFile, $stream);

                if (\is_resource($stream)) {
                    fclose($stream);
                }
            }
        }

    }
}