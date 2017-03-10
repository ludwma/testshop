<?php

namespace App\Model;

use Nette;
use Nette\Application\UI\Form;
use App\kontakt;

class KontaktManager
{  
    use Nette\SmartObject;
    
    private $enmanager;
    private $database;

    //db inject
    public function __construct(Nette\Database\Context $database,\Kdyby\Doctrine\EntityManager $enmanager)
	{
		$this->database = $database;
		$this->enmanager = $enmanager;
	}

//function for editing contacts 
 public function edit($values)
        {
     try
     {
         $kontaktDao = $this->enmanager->getRepository(kontakt::class);
	 $kontakt = $kontaktDao->findOneBy(["jmeno"=>$values["sjmeno"]]);
	 $kontakt->jmeno = $values["njmeno"];
	 $kontakt->kontakt = $values["nkontakt"];
	 $this->enmanager->flush();
     } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){return FALSE;}
     return TRUE;
        } 
        
	//get contacts from db
        public function select()
        {
	    $kontaktDao = $this->enmanager->getRepository(kontakt::class);
	    $kontakty = $kontaktDao->findAll();
            return $kontakty;
        }
	
	//function for add contact
	public function add($values)
	{
	    try {
		$Kontakt = new kontakt;
		$Kontakt->jmeno = $values["jmeno"];
		$Kontakt->kontakt = $values["kontakt"];
		$Kontakt->umisteni = $values["pozice"];
		$this->enmanager->persist($Kontakt);
		$this->enmanager->flush();
	    }
 catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){		return FALSE;}
	return TRUE;
	}

	//function for delete contact
	public function delete($jmeno)
	{
	    try{
	$kontaktDao = $this->enmanager->getRepository(kontakt::class);
	$kontakt = $kontaktDao->findOneBy(["jmeno"=>$jmeno]);
	$this->enmanager->remove($kontakt);
	$this->enmanager->flush();
	    } catch (\Doctrine\ORM\ORMInvalidArgumentException $e){		return FALSE;}
	return TRUE;
	}
        }

