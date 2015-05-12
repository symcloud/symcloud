<?php

namespace Symcloud\Bundle\StorageBundle\Api;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use Symcloud\Component\MetadataStorage\Model\NodeInterface;

/**
 * @ExclusionPolicy("all")
 */
abstract class Node
{
    /**
     * @var NodeInterface
     */
    protected $node;

    /**
     * @var string
     */
    protected $name;

    /**
     * Node constructor.
     * @param NodeInterface $node
     * @param string $name
     */
    public function __construct(NodeInterface $node, $name)
    {
        $this->node = $node;
        $this->name = $name;
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getPath()
    {
        return $this->node->getPath();
    }
}
