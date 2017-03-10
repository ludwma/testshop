<?php

namespace App\Model;

use Nette;
use Nette\Application\UI\Form;
use App\games;

class GamesManager {

    use Nette\SmartObject;

    private $session;
    private $path;
    private $enmanager;

    //get administration sessions 
    //inject database context , and paths for saving images
    public function __construct($path, Nette\Http\Session $session,\Kdyby\Doctrine\EntityManager $enmanager) {
        $this->session = $session->getSection("administrace");
        $this->path = $path;
	$this->enmanager = $enmanager;
    }

    //function for add new game to database and upload images
    public function add($values) 
        {
        //test , whether images uploads are succes
        if (!$values["krabice"]->isOk() || !$values["nahled1"]->isOk() || !$values["nahled2"]->isOk() || !$values["nahled3"]->isOk() || !$values["nahled4"]->isOk()) {
            return FALSE;
        }
        try {
	    $games = new games;
	    $games->jmeno = $values["jmeno"];
	    $games->popis = $values["popis"];
	    $games->cena = $values["cena"];
	    $games->zanr = $values["zanr"];
	    $games->verze = $values["verze"];
	    $games->vyslo = new Nette\Utils\DateTime($values["datum"]);
	    $games->jazyk = $values["jazyk"];
	    $games->vydani = $values["vydani"];
	    $games->skladem = $values["skladem"];
	    $games->idyt = $values["ytid"];
	    $this->enmanager->persist($games);
	    $this->enmanager->flush();
	    
            $values["krabice"]->move($this->path . "/" . $games->id . "/Krabice.jpg");
            $values["nahled1"]->move($this->path . "/" . $games->id . "/Nahled1.jpg");
            $values["nahled2"]->move($this->path . "/" . $games->id . "/Nahled2.jpg");
            $values["nahled3"]->move($this->path . "/" . $games->id . "/Nahled3.jpg");
            $values["nahled4"]->move($this->path . "/" . $games->id . "/Nahled4.jpg");
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return false;
        }
        return true;
    }

    //function for select games from database , by name , genre and name of game
    //return 2dimensional arry , except select by id , that return 1dimensional array
    public function select($hra, $zanr, $id) {
	
	 $gamesDao = $this->enmanager->getRepository(games::class);
	
        if ($hra && !$zanr && !$id) {
            $game = $gamesDao->findBy(["jmeno"=>$hra]);
            return $game;
        }
        if ($zanr && !$hra && !$id) {
            $game=$gamesDao->findBy(["zanr"=>$zanr]);
            return $game;
        }
        if (!$hra && !$zanr && !$id) {
            $game = $gamesDao->findAll();
            return $game;
        }
        if ($id && !$zanr && !$hra) {
            $game = $gamesDao->findOneBy(["id"=>$id]);
            return $game;
        }
    }
    
    //function for advanced selecting
    //tady je asi jednoduší použít nette database context
    //muzu zanyst subquery do where , ale nechci jich mit v sobe nekolik
    public function advancedSelect($maxcena,$values)
    {
    
    //nastaveni ceny
  if(!isset ($maxcena))
  {
      $maxcena = 9999999;
  }
  //nastaveni zanru
  $zanry = array_slice($values,2,7);
    $qb = $this->enmanager->createQueryBuilder()
	    ->select("u")
	    ->from(games::class, "u")
	    ->where("u.cena <= ".$maxcena)
	    ->andWhere("u.zanr = 'adventura OR akcni'")
	    ->getDQL();
    $games = $this->enmanager->createQuery($qb)->getResult();
    return $games;
    
    
    }

    //delete game
    public function delete($hra) {
	try{
      $gamesDao = $this->enmanager->getRepository(games::class);
      $game = $gamesDao->findOneBy(["jmeno"=>$hra]);
      $this->enmanager->remove($game);
	$this->enmanager->flush();}
 catch (\Doctrine\ORM\ORMInvalidArgumentException $e)
 {
    return FALSE;
 }
 return TRUE;
    }
    
    //delete images linked to game
    public function OdstranObrazky($jmeno) {
      $gamesDao = $this->enmanager->getRepository(games::class);
      $info = $gamesDao->findOneBy(["jmeno"=>$jmeno]);
      if($info)
      {
        unlink($this->path . "/" . $info->id . "/Krabice.jpg");
        unlink($this->path . "/" . $info->id . "/Nahled1.jpg");
        unlink($this->path . "/" . $info->id . "/Nahled2.jpg");
        unlink($this->path . "/" . $info->id . "/Nahled3.jpg");
        unlink($this->path . "/" . $info->id . "/Nahled4.jpg");
        rmdir($this->path . "/" . $info->id);
	return TRUE;
      }
 else {
	  return false;  
      }
    }


    //edit game , only text items , no images
    public function edit($values) {
	try{
            $gameDao = $this->enmanager->getRepository(games::class);
	    $games = $gameDao->findOneBy(["jmeno"=> $this->session["meno"]]);
	    $games->jmeno = $values["jmeno"];
	    $games->popis = $values["popis"];
	    $games->cena = $values["cena"];
	    $games->zanr = $values["zanr"];
	    $games->verze = $values["verze"];
	    $games->vyslo = new Nette\Utils\DateTime($values["datum"]);
	    $games->jazyk = $values["jazyk"];
	    $games->vydani = $values["vydani"];
	    $games->skladem = $values["skladem"];
	    $games->idyt = $values["ytid"];
	    $this->enmanager->flush();
	} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){	    return FALSE;}
return TRUE;
    }

}
