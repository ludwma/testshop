<?php
namespace App\Model;
use Nette;


class PokladnaManager {
    private $enmanager;
    public function __construct(\Kdyby\Doctrine\EntityManager $enmanager) {
	$this->enmanager = $enmanager;
    }
    
    //zisti jestli je v kosiku obsazena ehra
    public function testujEhru($kosik)
    {
	$Ehra = FALSE;
	$vsechnoE = TRUE;
	{
	    $gamesDao = $this->enmanager->getRepository(\App\games::class);
	    foreach ($kosik as $polozka)
	    {
		$game = $gamesDao->findOneBy(["id"=>$polozka]);
		if($game->vydani == "Steam")
		{
		    $Ehra = TRUE;
		}
 else {
		$vsechnoE = FALSE;    
		}
	    }
	}
	return $info = array("ehra"=>$Ehra,"vsechnoe"=>$vsechnoE);
    }
}
