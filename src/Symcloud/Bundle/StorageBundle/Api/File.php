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

use Hateoas\Configuration\Annotation\Relation;
use Hateoas\Configuration\Annotation\Route;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use Symcloud\Component\Database\Model\Tree\TreeFileInterface;

/**
 * @ExclusionPolicy("all")
 * @Relation(
 *      "self",
 *      href = @Route(
 *         "get_file",
 *         parameters = { "path" = "expr(object.getPathWithoutStartingSlash())", "reference" = "expr(object.getReferenceHash())" }
 *     )
 * )
 */
class File extends Node
{
    /**
     * @var TreeFileInterface
     */
    protected $node;

    /**
     * File constructor.
     *
     * @param TreeFileInterface $file
     * @param string $name
     * @param string $referenceHash
     */
    public function __construct(TreeFileInterface $file, $name, $referenceHash)
    {
        parent::__construct($file, $name, $referenceHash);
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getFileHash()
    {
        return $this->node->getFileHash();
    }

    /**
     * @return int
     *
     * @VirtualProperty()
     */
    public function getVersion()
    {
        return $this->node->getVersion();
    }

    /**
     * @return int
     *
     * @VirtualProperty()
     */
    public function getSize()
    {
        return $this->node->getSize();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getMimetype()
    {
        return $this->node->getMimetype();
    }
}
