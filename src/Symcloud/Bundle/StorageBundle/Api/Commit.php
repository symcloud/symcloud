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
use Symcloud\Component\Database\Model\Commit\CommitInterface;

/**
 * @ExclusionPolicy("all")
 */
class Commit
{
    /**
     * @var CommitInterface
     */
    private $commit;

    /**
     * Commit constructor.
     *
     * @param CommitInterface $commit
     */
    public function __construct(CommitInterface $commit)
    {
        $this->commit = $commit;
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getHash()
    {
        return $this->commit->getHash();
    }

    /**
     * @return string
     *
     * @VirtualProperty()
     */
    public function getUsername()
    {
        return $this->commit->getCommitter()->getUsername();
    }

    /**
     * @return \DateTime
     *
     * @VirtualProperty()
     */
    public function getCreatedAt()
    {
        return $this->commit->getCreatedAt();
    }
}
