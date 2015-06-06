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

use Hateoas\Configuration\Annotation\Exclusion;
use Hateoas\Configuration\Annotation\Relation;
use Hateoas\Configuration\Annotation\Route;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use Symcloud\Component\Database\Model\Reference\ReferenceInterface;
use Symcloud\Component\Database\Model\Tree\TreeNodeInterface;

/**
 * @ExclusionPolicy("all")
 * @Relation(
 *      "self",
 *      href = @Route(
 *         "get_reference",
 *         parameters = { "hash" = "expr(object.getHash())" }
 *     )
 * )
 * @Relation(
 *      "children",
 *      embedded = "expr(object.getChildren())",
 *      exclusion = @Exclusion(excludeIf = "expr(!object.isWithChildren())")
 * )
 */
class Reference
{
    /**
     * @var ReferenceInterface
     */
    private $reference;

    /**
     * @var bool
     */
    private $withChildren;

    /**
     * @var int
     */
    private $depth;

    /**
     * Reference constructor.
     *
     * @param ReferenceInterface $reference
     * @param bool $withChildren
     * @param int $depth
     */
    public function __construct(ReferenceInterface $reference, $withChildren = false, $depth = 0)
    {
        $this->reference = $reference;
        $this->withChildren = $withChildren;
        $this->depth = $depth;
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

    /**
     * @return bool
     */
    public function isWithChildren()
    {
        return $this->withChildren;
    }

    /**
     * @return Node[]
     */
    public function getChildren()
    {
        $children = array();
        foreach ($this->reference->getCommit()->getTree()->getChildren() as $name => $child) {
            if ($child->getType() === TreeNodeInterface::TREE_TYPE && ($this->depth > 0 || $this->depth === -1)) {
                $children[$name] = new self($child, $name, ($this->depth === -1 ? $this->depth : $this->depth - 1));
            } elseif ($child->getType() === TreeNodeInterface::FILE_TYPE) {
                $children[$name] = new File($child, $name);
            }
            // TODO TreeReferenceInterface
        }

        return $children;
    }
}
