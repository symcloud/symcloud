<?php

namespace Symcloud\Bundle\OAuth2Bundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    protected $id;

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
}
