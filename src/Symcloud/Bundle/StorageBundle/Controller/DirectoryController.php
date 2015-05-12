<?php

namespace Symcloud\Bundle\StorageBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use Symcloud\Bundle\StorageBundle\Api\Directory;
use Symfony\Component\HttpFoundation\Response;

class DirectoryController extends BaseStorageController
{
    /**
     * @Get("/directory/{path}", requirements={"path" = ".+"}, defaults={"path" = ""})
     * @param string $path
     * @return Response
     */
    public function getAction($path)
    {
        $path = '/' . $path;
        $session = $this->getSession();
        $tree = $session->getDirectory($path);

        return $this->handleView($this->view(new Directory($tree, basename($path))));
    }
}
