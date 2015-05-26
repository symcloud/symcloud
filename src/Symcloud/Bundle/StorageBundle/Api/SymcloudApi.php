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

use GuzzleHttp\Client;
use Symcloud\Component\Database\Replication\ApiInterface;
use Symcloud\Component\Database\Replication\ServerInterface;
use Symfony\Component\Routing\RouterInterface;

class SymcloudApi implements ApiInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * SymcloudApi constructor.
     *
     * @param Client $client
     * @param RouterInterface $router
     */
    public function __construct(Client $client, RouterInterface $router)
    {
        $this->client = $client;
        $this->router = $router;
    }

    public function store($hash, $data, ServerInterface $server)
    {
        $route = $this->router->generate('post_object');
        $this->client->post(
            $server->getUrl($route),
            array('json' => array('data' => $data), 'cookies' => array('XDEBUG_SESSION' => 'XDEBUG_ECLIPSE'))
        );
    }

    public function fetch($hash, $class, ServerInterface $server)
    {
        $route = $this->router->generate('get_object', array('hash' => $hash, 'class' => $class));
        $response = $this->client->get($server->getUrl($route));

        return $response->getBody()->getContents();
    }
}
