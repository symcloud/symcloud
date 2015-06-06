<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\StorageBundle\Controller;

class ReferenceController extends BaseStorageController
{
    public function cgetAction()
    {
        $session = $this->getSession();
        $references = $session->getReferences();

        $result = array();
        foreach ($references as $reference) {
            $result[] = $reference->getHash();
        }

        return $this->handleView($this->view($result));
    }
}
