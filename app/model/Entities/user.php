<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class user extends \Kdyby\Doctrine\Entities\BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
protected $user_id;

/**
 * @ORM\Column(type="string",length=15,unique=true)
 */
protected $username;

/**
 * @ORM\Column(type="string",length=30)
 */
protected $email;
/**
 * @ORM\Column(type="string",length=512,unique=true)
 */
protected $password;
/**
 * @ORM\Column(type="string", columnDefinition="ENUM('member', 'admin')")
 */
protected $role;
/**
 * @ORM\Column(type="datetime")
 */
protected $lastlogin;

}
