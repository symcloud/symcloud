<?php

namespace Symcloud\Bundle\StorageBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use Symcloud\Bundle\StorageBundle\Api\File;
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
        $file = $session->getFile($path);

        return $this->handleView($this->view(new File($file, $file->getName())));
    }
}
