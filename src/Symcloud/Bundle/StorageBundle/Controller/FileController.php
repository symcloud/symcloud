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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends BaseStorageController
{
    /**
     * @Get("/directory/{path}", requirements={"path" = ".+"}, defaults={"path" = ""})
     *
     * @param string $path
     *
     * @return Response
     */
    public function getAction($path)
    {
        $path = '/' . $path;
        $session = $this->getSession();
        $file = $session->getFile($path);

        return $this->handleView($this->view(new File($file, $file->getName())));
    }

    public function cpatchAction(Request $request)
    {
        $commands = $request->request->all();

        return $this->handleView($this->view(array()));
    }
}
