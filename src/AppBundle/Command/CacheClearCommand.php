<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand as CacheClearCommandSymfony;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;


class CacheClearCommand extends CacheClearCommandSymfony
{
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        parent::execute($input, $output);

        /** @var Filesystem $filesystem */
        $filesystem = $this->getContainer()->get('filesystem');

        $filesystem->chmod('var/', 0777, 0000, true);
        $filesystem->chown('var/', 1000, true);
        $filesystem->chmod('web/upload/', 0777, 0000, true);
        $filesystem->chown('web/upload/', 1000, true);
        $filesystem->chmod('web/cache/', 0777, 0000, true);
        $filesystem->chown('web/cache/', 1000, true);

    }
}