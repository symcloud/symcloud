<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\StorageBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearStorageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('symcloud:storage:clear');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Clear data ...');
        $database = $this->getContainer()->get('symcloud_storage.database');
        $database->deleteAll();
        $output->writeln('Finished');
    }
}
