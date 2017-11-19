<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see http://flysystem.thephpleague.com/api/#general-usage
 *
 * Class RestoreCommand
 * @package AppBundle\Command
 */
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
        if (!$name) {
            return 1;
        }

        /**
         * first download sql.
         */
        $filesystem = $this->getContainer()->get('oneup_flysystem.dropbox_filesystem');
        if (!$filesystem->has($name)) {
            echo "file $name not exists ! \n";
            return 1;
        }

        $localFileName = $this->getContainer()->getParameter('app.backup_dir') . $name;

        $stream = $filesystem->readStream($name);
        $contents = stream_get_contents($stream);
        fclose($stream);
        if(!file_exists(dirname($localFileName))) {
            mkdir(dirname($localFileName), 0777, true);
        }

        file_put_contents($localFileName, $contents);

        /** then restore bdd */

        $restore = $this->getContainer()->get('backup_manager')->makeRestore();
        $restore->run('local', $name, 'production', 'gzip');


    }
}