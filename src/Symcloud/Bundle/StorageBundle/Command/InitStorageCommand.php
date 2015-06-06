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

use Symcloud\Component\Session\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class InitStorageCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('symcloud:storage:init')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('name', InputArgument::OPTIONAL, '', 'HEAD');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        /** @var UserProviderInterface $userProvider */
        $userProvider = $this->getContainer()->get('sulu_security.user_repository');
        /** @var RepositoryInterface $sessionRepository */
        $sessionRepository = $this->getContainer()->get('symcloud_storage.session_repository');

        $session = $sessionRepository->loginByName(
            $userProvider->loadUserByUsername($username),
            $input->getArgument('name')
        );
        $session->init();

        $reference = $session->getReference();

        $output->writeln(sprintf('Storage of user "%s" initiated', $username));
        $output->writeln(sprintf('Name of new reference: "%s"', $reference->getName()));
        $output->writeln(sprintf('Hash of new reference: "%s"', $reference->getHash()));
    }
}
