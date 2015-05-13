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

use Riak\Client\Command\Kv\DeleteValue;
use Riak\Client\Command\Kv\ListKeys;
use Riak\Client\Command\Kv\Response\ListKeysResponse;
use Riak\Client\Core\Query\RiakLocation;
use Riak\Client\Core\Query\RiakNamespace;
use Riak\Client\RiakClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearRiakCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('symcloud:storage:clear');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $riak = $this->getContainer()->get('symcloud_storage.riak');
        $blobNamespace = $this->getContainer()->get('symcloud_storage.riak.namespace.blob');
        $blobFileNamespace = $this->getContainer()->get('symcloud_storage.riak.namespace.blob_file');
        $metadataNamespace = $this->getContainer()->get('symcloud_storage.riak.namespace.metadata');

        $output->writeln('Clear blobs:');
        $this->clearBucket($blobNamespace, $riak, $output);

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Clear files:');
        $this->clearBucket($blobFileNamespace, $riak, $output);

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Clear metadata:');
        $this->clearBucket($metadataNamespace, $riak, $output);
    }

    protected function clearBucket(RiakNamespace $namespace, RiakClient $riak, OutputInterface $output)
    {
        $progress = new ProgressBar($output);
        $response = $this->fetchBucketKeys($namespace, $riak);
        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $progress->start(count($response));

        foreach ($response as $key) {
            try {
                $this->deleteObject($key, $namespace, $riak);
                $progress->advance();
            } catch (\Exception $ex) {
            }
        }
        $progress->finish();
    }

    protected function fetchBucketKeys(RiakNamespace $namespace, RiakClient $riak)
    {
        $fetch = ListKeys::builder($namespace)->build();

        $keys = array();
        /** @var ListKeysResponse $response */
        $response = $riak->execute($fetch);
        foreach ($response->getIterator() as $location) {
            $keys[] = $location->getKey();
        }

        return $keys;
    }

    protected function deleteObject($key, RiakNamespace $namespace, RiakClient $riak)
    {
        $location = new RiakLocation($namespace, $key);
        $delete = DeleteValue::builder($location)->build();

        return $riak->execute($delete);
    }
}
