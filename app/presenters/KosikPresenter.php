<?php
namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\GamesManager;


class KosikPresenter extends BasePresenter
{ 
    //get items from shopping cart
    public function renderDefault()
    {
     $polozky = array();
     $pozice = 0;
     $kosik = $this->getSession('kosik');
     $celkovacena = 0;
     foreach ($kosik as $polozka)
     {
     $polozky[$pozice] = $this->GamesManager->select("","",$polozka);
     $pozice = $pozice + 1;
     }
     $this->template->hry = $polozky;
     //ziskani celkove ceny
     foreach($polozky as $polozka)
     {
     if(!empty($polozka->cena))
     {
     $celkovacena = $celkovacena + $polozka->cena;
     }
     }
     $this->template->celkovacena = $celkovacena;
    }

    //hotovo
    //add item to shopping cart
    public function actionPridatDoKosiku($id)
    {   
    //dostanu session section kosik
     $kosik = $this->getSession('kosik'); 
     //ziskam jeho prvni volnou pozici
     $pozice = 0;
     while (!empty($kosik[$pozice]))
     {
     $pozice = $pozice + 1;
     }
     //hodim do ni id pridane hry
     $kosik[$pozice] = $id;
     //vypisu flashmessage
     $this->flashMessage("Hra byla přidána do košíku","blesk");
     //redirectnu
     $this->redirect('Produkt:default',$id);
    }
    
    //hotovo
    //form for delete one item from cart
    public function createComponentOdstranZboziForm()
    {
    //vytvoreni formu
    $form = new Form;
    //vytvoreni inputu
    $form->addHidden("id");
    $form->addSubmit("odstran");
    //pri uspesnem odeslani    
    $form->onSuccess[] = function($form,$values)
    {
    //dostanu session kosik
    $kosik = $this->getSession('kosik'); 
    //najdu na jake pozici je hledana polozka
    $pozice = 0;
    while($kosik[$pozice] != $values["id"])
    {
    $pozice = $pozice + 1;
    }
    //odstranim pozici s polozkou
    unset($kosik[$pozice]);
    };    
    return $form;
    }
    
    //tohle chce vylepseni
    //form for dele 100 items from cart
    public function createComponentVyhodKosikForm()
    {
        $form = new Form;
        $form->addSubmit("vyprazdnit");
        $form->onSuccess[] = function($form,$values){
        $kosik = $this->getSession('kosik');
        for($i = 0;$i<100;$i = $i + 1)
        {
            unset($kosik[$i]);
        }
        };
        return $form;
    }
    
    //funkce ktera preda presenteru informaci o tom jestli se v kosiku vyskytuje elektronicky
    //distribuovana hra
    public function renderPokladna()
    {
	$kosik = $this->getSession('kosik');
	$elektronka = FALSE;
	foreach ($kosik as $polozka)
	{
	    $this->template->info = $this->PokladnaManager->testujEhru($kosik);    
	}
    }
    
    public function createComponentPlatbaForm()
    {
	$form = new Form;
	$form->addRadioList("platba","",["prevodem","dobirka"])
	->addRule(Form::FILLED,"Zvolte prosím způsob platby");
	$form->addSubmit("odeslat");
	$form->onSuccess[] = function($form,$values)
	{
	    $pokladna = $this->getSession("pokladna");
	    $pokladna["platba"] = $values["platba"];
	    $this->redirect("Kosik:doprava");
	};
	return $form;
    }
    
}

