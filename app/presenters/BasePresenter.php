<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;

//base presenter
abstract class BasePresenter extends Nette\Application\UI\Presenter {

    //preparation variables for inject
    protected $UserManager;
    protected $GamesManager;
    protected $KontaktManager;
    protected $PokladnaManager;
    protected $User;

    //inject models for all presenters
    public function __construct(Model\UserManager $UserManager, Model\GamesManager $GamesManager, Model\KontaktManager $KontaktManager,Model\PokladnaManager $p) {
        parent::__construct();
        $this->UserManager = $UserManager;
        $this->GamesManager = $GamesManager;
        $this->KontaktManager = $KontaktManager;
	$this->PokladnaManager = $p;
    }

    //funkce sice neodesila pole , ale vzdy bude stejna , tak to nevadi
    //login form , is available on all pages
    public function createComponentSignInForm() {
        //vytvoreni formulare
	$form = new Form;
        //vytvoreni inputu
        $form->addText("user")
                ->addRule(Form::FILLED, 'Vyplňte prosím vaše uživatelské jméno')
                ->addRule(Form::MAX_LENGTH, "Uživatelské jméno může mít maximálně %d znaků", 15);

        $form->addPassword("password")
                ->addRule(Form::FILLED, 'Vyplňte heslo')
                ->addRule(Form::MAX_LENGTH, "Heslo může mít maximálně %d znaků", 15);
	//pri uspesnem odeslani
        $form->onSuccess[] = function ($form, $values) {
	    //zkusi login
            try {
                $this->user->login($values["user"], $values["password"]);
            } catch (Nette\Security\AuthenticationException $e) {
		//nevyslo
                $this->flashMessage("Špatné jméno nebo heslo , zkuste to znovu", "bleskNe");
            }
        };
        return $form;
    }
    
    
    //tady budou funkce ktere budu pouzivat ve vetsine presenteru
    
    //funkce ktera provede pozadavek na model po odeslani formulare a pak vyhodi flashmassage podle toho jestli byl pozadavek splnen nebo ne
    protected function formflash($prikaz,$ano,$ne,$redirect)
    {
	if($prikaz)
	{
	    $this->flashMessage($ano,"blesk");
	}
	else
	{
	    $this->flashMessage($ne,"bleskNe");
	}
	$this->redirect("".$this->name.":".$redirect);
    }
}
