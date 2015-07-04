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
use Symcloud\Component\Database\Model\ChunkFileInterface;

/**
 * @ExclusionPolicy("all")
 */
class ChunkFile
{
    /**
     * @var ChunkFileInterface
     */
    private $chunkFile;

    /**
     * ChunkFile constructor.
     *
     * @param ChunkFileInterface $chunkFile
     */
    public function __construct(ChunkFileInterface $chunkFile)
    {
        $this->chunkFile = $chunkFile;
    }

    /**
     * @return string[]
     *
     * @VirtualProperty()
     */
    public function getChunks()
    {
        $result = array();
        foreach ($this->chunkFile->getChunks() as $chunk) {
            $result[] = $chunk->getHash();
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
        return $this->chunkFile->getHash();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getSize()
    {
        return $this->chunkFile->getSize();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getMimetype()
    {
        return $this->chunkFile->getMimetype();
    }
}
