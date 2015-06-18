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
 *         parameters = { "path" = "expr(object.getPathWithoutStartingSlash())", "reference" = "expr(object.getReferenceHash())" }
 *     )
 * )
 * @Relation(
 *      "children",
 *      href = @Route(
 *         "get_directory",
 *         parameters = { "path" = "expr(object.getPathWithoutStartingSlash())", "reference" = "expr(object.getReferenceHash())", "name-as-key" = "true", "only-directories" = "true" }
 *     )
 * )
 * @Relation(
 *      "admin",
 *      href ="expr('symcloud/path:'~object.getReferenceHash()~''~object.getPath())"
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
     * @var bool
     */
    private $nameAsKey;

    /**
     * @var bool
     */
    private $onlyDirectories;

    /**
     * @var bool
     */
    private $onlyFiles;

    /**
     * Directory constructor.
     *
     * @param TreeInterface $tree
     * @param string $name
     * @param string $referenceHash
     * @param int $depth
     * @param bool $nameAsKey
     * @param bool $onlyDirectories
     * @param bool $onlyFiles
     */
    public function __construct(
        TreeInterface $tree,
        $name,
        $referenceHash,
        $depth = -1,
        $nameAsKey = true,
        $onlyDirectories = false,
        $onlyFiles = false
    ) {
        parent::__construct($tree, $name, $referenceHash);

        $this->depth = $depth;
        $this->nameAsKey = $nameAsKey;
        $this->onlyDirectories = $onlyDirectories;
        $this->onlyFiles = $onlyFiles;
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
            if ($child->getType() === TreeNodeInterface::TREE_TYPE &&
                ($this->depth > 0 || $this->depth === -1) && !$this->onlyFiles
            ) {
                $children[$name] = new self(
                    $child,
                    $name,
                    $this->referenceHash,
                    ($this->depth === -1 ? $this->depth : $this->depth - 1),
                    $this->nameAsKey,
                    $this->onlyDirectories,
                    $this->onlyFiles
                );
            } elseif ($child->getType() === TreeNodeInterface::FILE_TYPE && !$this->onlyDirectories) {
                $children[$name] = new File($child, $name, $this->referenceHash);
            }
            // TODO TreeReferenceInterface
        }

        if ($this->nameAsKey) {
            return array_values($children);
        }

        return $children;
    }
}
