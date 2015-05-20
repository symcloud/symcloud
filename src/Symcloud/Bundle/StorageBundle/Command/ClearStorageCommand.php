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

use Symcloud\Component\Common\AdapterInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
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
        $blobAdapter = $this->getContainer()->get('symcloud_storage.adapter.blob');
        $blobFileAdapter = $this->getContainer()->get('symcloud_storage.adapter.blob_file');
        $metadataAdapter = $this->getContainer()->get('symcloud_storage.adapter.metadata');

        $output->writeln('Clear blobs:');
        $this->clearAdapter($blobAdapter, $output);

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Clear files:');
        $this->clearAdapter($blobFileAdapter, $output);

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Clear metadata:');
        $this->clearAdapter($metadataAdapter, $output);
    }

    protected function clearAdapter(AdapterInterface $adapter, OutputInterface $output)
    {
        $progress = new ProgressBar($output);
        $keys = $adapter->fetchHashes();
        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $progress->start(count($keys));

        foreach ($keys as $key) {
            try {
                $adapter->remove($key);
                $progress->advance();
            } catch (\Exception $ex) {
            }
        }
        $progress->finish();
    }
}
