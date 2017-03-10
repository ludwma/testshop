<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="kontakty")
 */
class kontakt extends \Kdyby\Doctrine\Entities\BaseEntity 
{
 /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
protected $id;
/**
 * @ORM\Column(type="string",length=20,unique=true)
 */
protected $jmeno;
/**
 * @ORM\Column(type="string",length=100)
 */
protected $kontakt;
/**
 * @ORM\Column(type="string",length=20)
 */
protected $umisteni;
}
