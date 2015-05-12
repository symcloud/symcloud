<?php

namespace Symcloud\Bundle\StorageBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Component\Rest\RestController;
use Symcloud\Component\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseStorageController extends RestController implements ClassResourceInterface
{
    /**
     * @param UserInterface $user
     * @return SessionInterface
     */
    protected function getSession(UserInterface $user = null)
    {
        $repository = $this->get('symcloud_storage.session_repository');
        return $repository->login($user ?: $this->getUser());
    }
}
