<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\GamesManager;

class HomepagePresenter extends BasePresenter {

    //vylepsit strankovani , jinak se uz menit nebude
    //get information about games , create simple pagination
    public function renderDefault($zanr, $hra, $astranka) {
	//pomucka pro strankovani
	$stranka = 0;
	//pomocne promenne
	$hry = array();
	$hryNaStranku = array();
	//uzivatel chce sobrazit hry urciteho zanru
	if ($zanr && !$hra) {
	    $hry = $this->GamesManager->select("", $zanr, "");
	    $hryNaStranku = array_slice($hry, $astranka * 12, 12);
	}
	//uzivatel chce zobrazit vsechny hry
	if (!$zanr && !$hra) {
	    $hry = $this->GamesManager->select("", "", "");
	    $hryNaStranku = array_slice($hry, $astranka * 12, 12);
	}
	//uzivatel chce sobrazit konkretni hru
	if ($hra && !$zanr) {
	    $hry = $this->GamesManager->select($hra, "", "");
	    $hryNaStranku = $hry;
	}
	//strankovani a neco malo dalsiho
	$this->template->hry = $hryNaStranku;
	if (!empty($hry)) {
	    for ($i = 0; $i < sizeof($hry); $i = $i + 12) {
		$stranka = $stranka + 1;
	    }
	}
	$this->template->stranky = $stranka;
	$this->template->zanr = $zanr;
	    }

        //form for find one specific game
        protected function createComponentHledejHruForm() {
	$form = new Form;
	$form->addText("hledana")
        ->addRule(Form::FILLED, 'Vyplňte prosím jméno hledané hry');
	$form->addSubmit("hledej");
	$form->onSuccess[] = function($form, $values) {
	//redirect kvuli zmene url
	$this->redirect("Homepage:default", "", $values["hledana"]);
	};
	return $form;
	
    }

    //form for advanced searching
    protected function createComponentHledejPodleDetailuForm() {
	//vytvoreni formu
	$form = new Form;
	//vytvoreni inputu

	$form->addText("maxcena")
		->setType("number");
        
	//zanry
	$form->addCheckbox("akcni");
	$form->addCheckbox("adventura");
	$form->addCheckbox("rpg");
	$form->addCheckbox("sportovni");
	$form->addCheckbox("simulator");
	$form->addCheckbox("plosinovka");
	$form->addCheckbox("strategie");

	//jazyky
	$form->addCheckbox("Cz");
	$form->addCheckbox("Anglicky");

	//distribuce
	$form->addCheckbox("krabice");
	$form->addCheckbox("Steam");

	//submit
	$form->addSubmit("Hledej");

	//pri uspesnem odeslani
	$form->onSuccess[] = function ($form,array $values) {
	$this->redirect("Homepage:detailniHledani",$values["maxcena"],$values);
	};
	return $form;
    }
    
    //Action for detailniHledani page
    //vybere hry podle zadanych specifikaci formulare nad touto funkci
    public function actionDetailniHledani($maxcena,array $values) {
    $games = $this->GamesManager->advancedSelect($maxcena,$values);
    $this->template->hry = $games;
   
    }
}
