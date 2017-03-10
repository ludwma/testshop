<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\GamesManager;


class KontaktPresenter extends BasePresenter
{ 
 //get contacts
 public function renderDefault()
 {
     $vsechnykontakty = $this->KontaktManager->select();
     $prava = array();
     $leva = array();
     //roztrideni kontaktu podle toho jestli budou zobrazeny na prave nebo leve strane
     foreach ($vsechnykontakty as $kontakt)
     {
     if($kontakt->umisteni == "prava")
     {
     $prava[] = $kontakt;
     }
     else {
     $leva[] = $kontakt;    
     }
     }
     $this->template->prava = $prava;
     $this->template->leva = $leva;
 }
}

