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
use Symcloud\Component\Database\Model\Tree\TreeInterface;
use Symcloud\Component\Database\Model\Tree\TreeNodeInterface;

/**
 * @ExclusionPolicy("all")
 * @Relation(
 *      "self",
 *      href = @Route(
 *         "get_directory",
 *         parameters = { "path" = "expr(object.getPath())", "reference" = "expr(object.getReferenceHash())" }
 *     )
 * )
 * @Relation(
 *      "children",
 *      embedded = "expr(object.getChildren())"
 * )
 */
class Directory extends Node
{
    /**
     * @var TreeInterface
     */
    protected $node;

    /**
     * @var int
     */
    protected $depth;

    /**
     * Directory constructor.
     *
     * @param TreeInterface $tree
     * @param string $name
     * @param string $referenceHash
     * @param int $depth
     */
    public function __construct(TreeInterface $tree, $name, $referenceHash, $depth = -1)
    {
        parent::__construct($tree, $name, $referenceHash);

        $this->depth = $depth;
    }

    /**
     * @return int
     *
     * @VirtualProperty()
     */
    public function getHasChildren()
    {
        return count($this->node->getChildren()) > 0;
    }

    /**
     * @return Node[]
     */
    public function getChildren()
    {
        $children = array();
        foreach ($this->node->getChildren() as $name => $child) {
            if ($child->getType() === TreeNodeInterface::TREE_TYPE && ($this->depth > 0 || $this->depth === -1)) {
                $children[$name] = new self($child, $name, $this->referenceHash, ($this->depth === -1 ? $this->depth : $this->depth - 1));
            } elseif ($child->getType() === TreeNodeInterface::FILE_TYPE) {
                $children[$name] = new File($child, $name, $this->referenceHash);
            }
            // TODO TreeReferenceInterface
        }

        return $children;
    }
}
