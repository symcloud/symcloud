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

use Sulu\Bundle\AdminBundle\Admin\Admin as BaseAdmin;
use Sulu\Bundle\AdminBundle\Navigation\DataNavigationItem;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;
use Symcloud\Component\Session\RepositoryInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SuluAdmin extends BaseAdmin
{
    /**
     * @param SecurityCheckerInterface $securityChecker
     * @param TokenStorageInterface $tokenStorage
     * @param RepositoryInterface $repository
     * @param Router $router
     * @param string $title
     */
    public function __construct(
        SecurityCheckerInterface $securityChecker,
        TokenStorageInterface $tokenStorage,
        RepositoryInterface $repository,
        Router $router,
        $title
    ) {
        $rootNavigationItem = new NavigationItem($title);
        $section = new NavigationItem('');

        $files = new NavigationItem('symcloud.file');
        $files->setIcon('folder-open');

        if ($securityChecker->hasPermission('symcloud.files', 'view')) {
            $session = $repository->loginByHash($tokenStorage->getToken()->getUser(), 'HEAD');
            $references = $session->getReferences();

            foreach ($references as $reference) {
                $referenceItem = new DataNavigationItem(
                    $reference->getName(),
                    $router->generate(
                        'get_directory',
                        array('reference' => $reference->getHash(), 'name-as-key' => 'true', 'only-directories' => 'true')
                    )
                );
                $referenceItem->setDataResultKey('children');
                $referenceItem->setAction('symcloud/path:' . $reference->getHash());
                $referenceItem->setShowAddButton(false);
                $files->addChild($referenceItem);
            }
        }

        if ($files->hasChildren()) {
            $rootNavigationItem->addChild($section);
            $section->addChild($files);
        }

        $this->setNavigation(new Navigation($rootNavigationItem));
    }

    /**
     * {@inheritdoc}
     */
    public function getJsBundleName()
    {
        return 'symcloudsulu';
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityContexts()
    {
        return array(
            'Sulu' => array(
                'Files' => array(
                    'symcloud.files',
                ),
            ),
        );
    }
}
