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

use Symcloud\Component\FileStorage\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class BlobController extends BaseStorageController
{
    public function postAction(Request $request)
    {
        /** @var UploadedFile $uploadFile */
        $uploadFile = $request->files->get('blob-file');

        $session = $this->getSession();
        $blobFile = $session->upload($uploadFile->getPathname(), $uploadFile->getMimeType(), $uploadFile->getSize());

        return $this->handleView(
            $this->view(
                array(
                    'hash' => $blobFile->getFileHash(),
                    'mimetype' => $blobFile->getMimetype(),
                    'size' => $blobFile->getSize(),
                )
            )
        );
    }

    public function headAction($hash)
    {
        $session = $this->getSession();
        try {
            $blobFile = $session->downloadByHash($hash);
        } catch (FileNotFoundException $ex) {
            return $this->handleView($this->view(null, 404));
        }

        return $this->handleView(
            $this->view(
                array(
                    'hash' => $blobFile->getFileHash(),
                    'mimetype' => $blobFile->getMimetype(),
                    'size' => $blobFile->getSize(),
                )
            )
        );
    }
}
