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

use FOS\RestBundle\Controller\Annotations\Get;
use Symcloud\Bundle\StorageBundle\Api\File;
use Symcloud\Component\Database\Model\Tree\TreeFileInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends BaseStorageController
{
    /**
     * @Get("/file/{path}", requirements={"path" = ".+"}, defaults={"path" = ""})
     *
     * @param Request $request
     * @param string $path
     *
     * @return Response
     */
    public function getAction(Request $request, $path)
    {
        $path = '/' . $path;
        // TODO add ß, ä, ö, perhaps other special chars
        $path = urldecode(
            str_replace(array('u%CC_', 'a%CC_', 'o%CC_', '%C3_'), array('ü', 'ä', 'ö', 'ß'), urlencode($path))
        );

        $session = $this->getSession();
        $file = $session->getFile($path);

        if ($request->get('content') !== null) {
            return $this->getContent($file);
        }

        return $this->handleView($this->view(new File($file, $file->getName())));
    }

    private function getContent(TreeFileInterface $file)
    {
        $response = new Response($file->getContent());
        $response->headers->set('Content-Type', $file->getMimeType());

        return $response;
    }

    public function cpatchAction(Request $request)
    {
        $commands = $request->request->get('commands');
        $session = $this->getSession();
        foreach ($commands as $command) {
            switch ($command['command']) {
                case 'post':
                    $session->createOrUpdateFile($command['path'], $command['file']);
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
