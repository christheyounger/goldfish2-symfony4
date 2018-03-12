<?php

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class OAuthAccessToken extends BaseAccessToken
{
	/**
	 * @ORM\ID()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\ManyToOne(targetEntity="OAuthClient")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $client;
	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $user;
}
