<?php

namespace Symcloud\Bundle\StorageBundle\Api;

use Hateoas\Configuration\Annotation\Relation;
use Hateoas\Configuration\Annotation\Route;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symcloud\Component\MetadataStorage\Model\TreeFileInterface;
use Symcloud\Component\MetadataStorage\Model\TreeInterface;

/**
 * @ExclusionPolicy("all")
 * @Relation(
 *      "self",
 *      href = @Route(
 *         "get_directory",
 *         parameters = { "path" = "expr(object.getPath())" }
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
     * Directory constructor.
     * @param TreeInterface $tree
     * @param string $name
     */
    public function __construct(TreeInterface $tree, $name)
    {
        parent::__construct($tree, $name);
    }

    /**
     * @return Node[]
     */
    public function getChildren()
    {
        $children = array();

        foreach ($this->node->getChildren() as $name => $child) {
            if ($child instanceof TreeInterface) {
                $children[] = new Directory($child, $name);
            } elseif ($child instanceof TreeFileInterface) {
                $children[] = new File($child, $name);
            }
            // TODO TreeReferenceInterface
        }

        return $children;
    }
}
