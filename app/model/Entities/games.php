<?php
namespace App;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class games extends \Kdyby\Doctrine\Entities\BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
  protected $id;
  
  /**
   *
   * @ORM\Column(type="string",length=100,unique=true)
   */
  protected $jmeno;
   /**
   *
   * @ORM\Column(type="text",unique=false)
   */
  protected $popis;
  /**
   *
   * @ORM\Column(type="integer")
   */
  protected $cena;
  /**
   *
   * @ORM\Column(type="string",length=50)
   */
  protected $zanr;
  /**
   *
   * @ORM\Column(type="string",length=20)
   */
  protected $verze;
  /**
   *
   * @ORM\Column(type="date")
   */
  protected $vyslo;
  /**
   *
   * @ORM\Column(type="string",length=20)
   */
  protected $jazyk;
   /**
   *
   * @ORM\Column(type="string",length=50)
   */
  protected $vydani;
   /**
   *
   * @ORM\Column(type="integer")
   */
  protected $skladem;
   /**
   *
   * @ORM\Column(type="string",length=100)
   */
  protected $idyt;
}
