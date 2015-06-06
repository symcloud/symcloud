<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\StorageBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Component\Rest\RestController;
use Symcloud\Component\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseStorageController extends RestController implements ClassResourceInterface
{
    /**
     * @param UserInterface $user
     *
     * @return SessionInterface
     */
    public function getSession(UserInterface $user = null)
    {
        $repository = $this->get('symcloud_storage.session_repository');

        return $repository->loginByName($user ?: $this->getUser(), 'HEAD');
    }

    /**
     * @param string $name
     * @param UserInterface $user
     *
     * @return SessionInterface
     */
    protected function getSessionByName($name, UserInterface $user = null)
    {
        $repository = $this->get('symcloud_storage.session_repository');

        return $repository->loginByName($user ?: $this->getUser(), $name);
    }

    /**
     * @param string $referenceHash
     * @param UserInterface $user
     *
     * @return SessionInterface
     */
    protected function getSessionByHash($referenceHash, UserInterface $user = null)
    {
        $repository = $this->get('symcloud_storage.session_repository');

        return $repository->loginByHash($user ?: $this->getUser(), $referenceHash);
    }
}
