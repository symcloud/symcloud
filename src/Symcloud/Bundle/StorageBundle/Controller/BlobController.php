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

use Symcloud\Bundle\StorageBundle\Api\BlobFile;
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

        return $this->handleView($this->view(new BlobFile($blobFile)));
    }
}
