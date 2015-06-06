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
use Symcloud\Bundle\StorageBundle\Api\Directory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DirectoryController extends BaseStorageController
{
    /**
     * @Get("/directory/{reference}/{path}", requirements={"path" = ".+"}, defaults={"path" = ""})
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
        $session = $this->getSessionByHash($reference);
        $tree = $session->getDirectory($path);

        return $this->handleView(
            $this->view(new Directory($tree, basename($path), $reference, $request->get('depth', -1)))
        );
    }
}
