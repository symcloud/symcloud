<?php

namespace Symcloud\Bundle\StorageBundle\Api;

use Hateoas\Configuration\Annotation\Relation;
use Hateoas\Configuration\Annotation\Route;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symcloud\Component\MetadataStorage\Model\TreeFileInterface;

/**
 * @ExclusionPolicy("all")
 * @Relation(
 *      "self",
 *      href = @Route(
 *         "get_directory",
 *         parameters = { "path" = "expr(object.getPath())" }
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
     * @param TreeFileInterface $file
     * @param string $name
     */
    public function __construct(TreeFileInterface $file, $name)
    {
        parent::__construct($file, $name);
    }
}
