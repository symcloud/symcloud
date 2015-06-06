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
use Symcloud\Component\Database\Model\Reference\ReferenceInterface;

/**
 * @ExclusionPolicy("all")
 * @Relation(
 *      "self",
 *      href = @Route(
 *         "get_reference",
 *         parameters = { "hash" = "expr(object.getHash())" }
 *     )
 * )
 */
class Reference
{
    /**
     * @var ReferenceInterface
     */
    private $reference;

    /**
     * Reference constructor.
     *
     * @param ReferenceInterface $reference
     */
    public function __construct(ReferenceInterface $reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getHash()
    {
        return $this->reference->getHash();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getName()
    {
        return $this->reference->getName();
    }
}
