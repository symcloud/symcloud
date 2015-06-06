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

use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Symcloud\Bundle\StorageBundle\Api\Reference;
use Symfony\Component\HttpFoundation\Request;

class ReferenceController extends BaseStorageController
{
    public function postAction(Request $request)
    {
        $session = $this->getSessionByName($request->get('name', 'HEAD'));

        if (false === $session->isInit()) {
            $session->init();
        }

        $reference = $session->getReference();

        return $this->handleView($this->view(new Reference($reference)));
    }

    public function getAction($hash)
    {
        $session = $this->getSessionByHash($hash);
        $reference = $session->getReference();

        return $this->handleView($this->view(new Reference($reference, true)));
    }

    public function cgetAction()
    {
        $session = $this->getSession();
        $references = $session->getReferences();

        $result = array();
        foreach ($references as $reference) {
            $result[] = new Reference($reference);
        }

        $presentation = new ListRepresentation($result, 'references', 'get_references', array(), 1, 9999, sizeof($result));

        return $this->handleView($this->view($presentation));
    }

    public function patchAction($hash, Request $request)
    {
        $commands = $request->request->get('commands');
        $session = $this->getSessionByHash($hash);
        foreach ($commands as $command) {
            switch ($command['command']) {
                case 'post':
                    $blobFile = $session->createBlobFile(
                        $command['file']['hash'],
                        $command['file']['blobs'],
                        $command['file']['mimetype'],
                        $command['file']['size']
                    );
                    $session->createOrUpdateFile($command['path'], $blobFile);
                    break;
                case 'delete':
                    $session->deleteFile($command['path']);
                    break;
                case 'commit':
                    $session->commit($command['message']);
                    break;
            }
        }

        return $this->handleView($this->view(null));
    }
}
