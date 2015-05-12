<?php

namespace Symcloud\Bundle\OAuth2Bundle\Command;

use FOS\OAuthServerBundle\Model\ClientManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('symcloud:oauth2:create-client')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('redirect-uris', InputArgument::IS_ARRAY | InputArgument::REQUIRED)
            ->addOption(
                'grant-type',
                'g',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                '',
                array('refresh_token', 'password')
            )
            ->addOption('cli', 'c', InputOption::VALUE_NONE, '');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $cli = $input->getOption('cli');
        $grantTypes = $input->getOption('grant-type');
        $container = $this->getContainer();

        /** @var ClientManager $clientManager */
        $clientManager = $container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris($input->getArgument('redirect-uris'));
        $client->setAllowedGrantTypes($grantTypes);
        $client->setName($name);
        $client->setCli($cli);
        $clientManager->updateClient($client);

        $output->writeln(sprintf('Client "%s" with grant-types "%s" generated', $name, join(', ', $grantTypes)));
        $output->writeln(
            sprintf('   public-id: "%s"%s   secret: "%s"', $client->getPublicId(), PHP_EOL, $client->getSecret())
        );
    }
}
