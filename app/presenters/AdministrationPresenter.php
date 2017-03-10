<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\GamesManager;

class AdministrationPresenter extends BasePresenter {

    //kick no admin users 
    protected function startup() {
	parent::startup();
	$user = $this->getUser();
	if (!$user->isInRole('admin')) {
	    $this->redirect("Nenipristup:default");
	}
    }

    //get values from contacts for inserting to form in administration section
    //tohle budu upravovat , do admin sekce pujdou vkladat jednotlive kontakty
    public function renderKontakty() {
	$this->template->kontakty = $this->KontaktManager->select();
    }

    //form for adding game , form have own class
    protected function createComponentPridatForm() {
	$form = Model\gameAddFormFactory::create();
	$form->onSuccess[] = function($form, $values) {
	    parent::formflash($this->GamesManager->add($values), "Hra byla úspěšně přidána", "Hra s tímto názcem již existuje", "pridat");
	};
	return $form;
    }

    //form for deleting the game
    public function createComponentOdstranForm() {
	$form = new Form;
	$form->addText("jmeno")
		->addRule(Form::FILLED, 'Vyplňte prosím jméno hry');
	$form->addSubmit("odstranit");
	$form->onSuccess[] = function($form, $values) {
	    //odstrani obrazky propojene se hrou
	    $this->GamesManager->OdstranObrazky($values["jmeno"]);
	    parent::formflash($this->GamesManager->delete($values["jmeno"]), "Hra byla úspěšně odstraněna", "Hra s tímto názvem neexistuje", "odstranit");
	};
	return $form;
    }

    //form for searching one game by name
    public function createComponentHledejForm() {
	$form = new Form;
	$form->addText("hledej")
		->addRule(Form::FILLED, 'Vyplňte prosím název hry');
	$form->addSubmit("hledat");
	$form->onSuccess[] = function($form, $values) {
	    //ziska session sekci
	    $sekce = $this->getSession()->getSection("administrace");
	    $sekce["meno"] = $values["hledej"];
	    //naplni template hra , cimkoli , aby se zobrazil formular pro upravu
	    $this->template->hra = "uspech";
	};
	return $form;
    }

    //form for editing game
    protected function createComponentUpravitForm() {
	//ziska session section administrace
	$sekce = $this->getSession()->getSection("administrace");
	$hodnoty = $this->GamesManager->select($sekce["meno"], "", "");
	//presune info o hre na klic 0 v poli , 
	usort($hodnoty, function($a, $b) {
	    return $a['order'] - $b['order'];
	});
	//zavola formular z modelu a preda mu hodnoty ktere budou nastaveny jako defaultni
	$form = Model\gameEditFormFactory::create($hodnoty[0]);
	//pri uspesnem odeslani
	$form->onSuccess[] = function($form, $values) {
	    parent::formflash($this->GamesManager->edit($values), "Hra byla úspěšně upravena", "Hra s tímto názvem již existuhe", "upravit");
	};
	return $form;
    }

    //formular pro pridani kontaktu
    public function createComponentPridatKontaktForm() {
	$form = new Form;
	$form->addText("jmeno")
		->addRule(Form::FILLED, 'Vyplňte prosím jméno kontaktu');
	$form->addText("kontakt")
		->addRule(Form::FILLED, 'Vyplňte prosím kontakt');
	$umisteni = [
	    "prava" => "Pravá strana",
	    "leva" => "Levá strana"];
	$form->addSelect("pozice", "pozice", $umisteni)
		->setPrompt("Zvolte umístění")
		->addRule(Form::FILLED, 'Vyberte umístění kontaktu');
	$form->addSubmit("odeslat");
	$form->onSuccess[] = function($form, $values) {
	    parent::formflash($this->KontaktManager->add($values), "Kontakt byl úspěšně přidán", "Kontakt s tímto jménem již existuje", "pridatkontakt");
	};
	return $form;
    }

    public function createComponentSmazatKontaktForm() {
	$form = new Form;

	$form->addText("jmeno")
		->addRule(Form::FILLED, 'Vyplňte prosím jméno hry kterou chcete smazat');
	$form->addSubmit("smaz");
	$form->onSuccess[] = function($form, $values) {
	    parent::formflash($this->KontaktManager->delete($values["jmeno"]), "Kontakt byl Úspěšně odstraněn", "Kontakt s tímto jménem neexistuje", "smazatkontakt");
	};
	return $form;
    }

    //formular pro upravu kontaktu   
    public function createComponentUpravitKontaktForm() {
	$form = new Form;
	$form->addText("sjmeno");
	$form->addText("njmeno");
	$form->addText("nkontakt");
	$form->onSuccess[] = function($form, $values) {
	    parent::formflash($this->KontaktManager->edit($values), "Kontakt byl úspěšně upraven", "Kontakt s tímto jménem už existuje", "upravitkontakt");
	};
	return $form;
    }

}
