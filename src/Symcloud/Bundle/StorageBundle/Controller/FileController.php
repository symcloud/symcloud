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

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symcloud\Bundle\StorageBundle\Api\Commit;
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

    /**
     * @Delete("/file/{reference}/{path}", requirements={"path" = ".+"})
     *
     * @param Request $request
     * @param string $reference
     * @param string $path
     *
     * @return Response
     */
    public function deleteAction(Request $request, $reference, $path)
    {
        $path = '/' . $path;
        // TODO add ß, ä, ö, perhaps other special chars
        $path = urldecode(
            str_replace(array('u%CC_', 'a%CC_', 'o%CC_', '%C3_'), array('ü', 'ä', 'ö', 'ß'), urlencode($path))
        );

        $message = $request->get('message', sprintf('Deleted file "%s"', $path));

        $session = $this->getSessionByHash($reference);
        $session->deleteFile($path);

        return $this->handleView($this->view(new Commit($session->commit($message))));
    }

    /**
     * @Post("/file/{reference}/{path}", requirements={"path" = ".+"})
     *
     * @param Request $request
     * @param string $reference
     * @param string $path
     *
     * @return Response
     */
    public function postAction(Request $request, $reference, $path)
    {
        $path = '/' . $path;
        // TODO add ß, ä, ö, perhaps other special chars
        $path = urldecode(
            str_replace(array('u%CC_', 'a%CC_', 'o%CC_', '%C3_'), array('ü', 'ä', 'ö', 'ß'), urlencode($path))
        );

        $message = $request->get('message', sprintf('Create or update file "%s"', $path));
        $content = $request->get('content');
        $mimetype = $request->get('mimetype', 'text/plain');

        $tempName = tempnam(sys_get_temp_dir(), 'upload');
        $temp = fopen($tempName, 'w');
        fwrite($temp, $content);
        fclose($temp);

        $session = $this->getSessionByHash($reference);
        $chunkFile = $session->upload($tempName, $mimetype, strlen($content));
        $session->createOrUpdateFile($path, $chunkFile);

        return $this->handleView($this->view(new Commit($session->commit($message))));
    }

    private function getContent(TreeFileInterface $file)
    {
        // TODO streaming
        $response = new Response($file->getContent());
        $response->headers->set('Content-Type', $file->getMimeType());

        return $response;
    }
}
