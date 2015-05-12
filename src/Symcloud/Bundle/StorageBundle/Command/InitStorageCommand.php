<?php

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
            ->addArgument('username', InputArgument::REQUIRED);
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

        $session = $sessionRepository->login($userProvider->loadUserByUsername($username));
        $session->init();

        $output->writeln(sprintf('Storage of user "%s" initiated', $username));
    }
}
