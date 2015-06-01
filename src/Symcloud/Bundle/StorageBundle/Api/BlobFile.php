<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\StorageBundle\Api;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use Symcloud\Component\Database\Model\BlobFileInterface;

/**
 * @ExclusionPolicy("all")
 */
class BlobFile
{
    /**
     * @var BlobFileInterface
     */
    private $blobFile;

    /**
     * BlobFile constructor.
     *
     * @param BlobFileInterface $blobFile
     */
    public function __construct(BlobFileInterface $blobFile)
    {
        $this->blobFile = $blobFile;
    }

    /**
     * @return string[]
     *
     * @VirtualProperty()
     */
    public function getBlobs()
    {
        $result = array();
        foreach ($this->blobFile->getBlobs() as $blob) {
            $result[] = $blob->getHash();
        }

        return $result;
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getHash()
    {
        return $this->blobFile->getHash();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getSize()
    {
        return $this->blobFile->getSize();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getMimetype()
    {
        return $this->blobFile->getMimetype();
    }
}
