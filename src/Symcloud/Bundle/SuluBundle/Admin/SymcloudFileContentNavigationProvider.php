<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\SuluBundle\Admin;

use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem;
use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationProviderInterface;

class SymcloudFileContentNavigationProvider implements ContentNavigationProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNavigationItems(array $options = array())
    {
        $details = new ContentNavigationItem('symcloud.file.details');
        $details->setAction('details');
        $details->setComponent('file-edit@symcloudsulu');
        $details->setComponentOptions(array('display' => 'form'));

        return array($details);
    }
}
