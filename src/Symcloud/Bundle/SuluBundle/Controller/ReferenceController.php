<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\SuluBundle\Controller;

use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\RestController;

class ReferenceController extends RestController
{
    public function fieldsAction()
    {
        return $this->handleView(
            $this->view(
                array(
                    new DoctrineFieldDescriptor(
                        'name',
                        'name',
                        '',
                        'public.name',
                        array(),
                        false,
                        false,
                        'title'
                    ),
                    new DoctrineFieldDescriptor(
                        'size',
                        'size',
                        '',
                        'public.size',
                        array(),
                        false,
                        false,
                        'bytes'
                    ),
                )
            )
        );
    }
}
