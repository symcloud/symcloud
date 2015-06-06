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
     * @Get("/file/{reference}/{path}", requirements={"path" = ".+"})
     *
     * @param Request $request
     * @param string $reference
     * @param string $path
     *
     * @return Response
     */
    public function getAction(Request $request, $reference, $path)
    {
        $path = '/' . $path;
        // TODO add ß, ä, ö, perhaps other special chars
        $path = urldecode(
            str_replace(array('u%CC_', 'a%CC_', 'o%CC_', '%C3_'), array('ü', 'ä', 'ö', 'ß'), urlencode($path))
        );

        $session = $this->getSessionByHash($reference);
        $file = $session->getFile($path);

        if ($request->get('content') !== null) {
            return $this->getContent($file);
        }

        return $this->handleView($this->view(new File($file, $file->getName(), $reference)));
    }

    private function getContent(TreeFileInterface $file)
    {
        // TODO streaming
        $response = new Response($file->getContent());
        $response->headers->set('Content-Type', $file->getMimeType());

        return $response;
    }
}
