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
use Symcloud\Component\Database\Replication\Exception\NotPrimaryServerException;
use Symcloud\Component\Database\Replication\Exception\ObjectNotFoundException;
use Symfony\Component\HttpFoundation\Request;

// TODO security

class ObjectController extends RestController implements ClassResourceInterface
{
    public function getAction($hash, Request $request)
    {
        $class = $request->get('class');
        $username = $request->get('username');

        $logger = $this->get('logger');
        $logger->warn(json_encode(array('class' => $class, 'username' => $username)));
        $logger->warn($request->getUri());

        $replicator = $this->container->get('symcloud_storage.database.replicator');

        try {
            $data = $replicator->fetch($hash, $class, $username);
        } catch (NotPrimaryServerException $ex) {
            return $this->redirect(
                sprintf(
                    '%s:%s/%s?%s',
                    $ex->getPrimaryServer()->getHost(),
                    $ex->getPrimaryServer()->getPort(),
                    ltrim($request->getPathInfo(), '/'),
                    $request->getQueryString()
                )
            );
        } catch (ObjectNotFoundException $ex) {
            return $this->handleView($this->view(null, 404));
        }

        return $this->handleView($this->view($data));
    }

    public function postAction(Request $request)
    {
        $hash = $request->get('hash');
        $data = $request->get('data');

        $replicator = $this->container->get('symcloud_storage.database.replicator');
        $view = $this->view($replicator->store($hash, $data));

        return $this->handleView($view);
    }
}
