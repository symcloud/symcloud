<?php

namespace Symcloud\Bundle\StorageBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;

class FileController extends BaseStorageController
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

        return $this->handleView($this->view($session->getFile($path)->toArray()));
    }
}
