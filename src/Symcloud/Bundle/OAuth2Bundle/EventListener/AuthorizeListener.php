<?php

namespace Symcloud\Bundle\OAuth2Bundle\EventListener;

use FOS\OAuthServerBundle\Event\OAuthEvent;

class AuthorizeListener
{
    public function preAuthorization(OAuthEvent $event)
    {
        if ($event->getClient()->getCli()) {
            $event->setAuthorizedClient(true);
        }
    }
}
