<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;

class RegisterPresenter extends BasePresenter
{
    //form for user registration
    protected function createComponentRegistrationForm()
    {
    $form = new Form;
    $form->addText("user")
        ->addRule(Form::FILLED, 'Vyplňte prosím vaše uživatelské jméno')
        ->addRule(Form::MAX_LENGTH,"Uživatelské jméno může mít maximálně %d znaků",15);
        
    $form->addEmail("email")
        ->addRule(Form::FILLED, 'Vyplňte váš email')
        ->addRule(Form::EMAIL, 'Opravte prosím chybu v emailové adrese')
        ->addRule(Form::MAX_LENGTH,"Email může mít maximálně %d znaků",30);
        
    $form->addPassword("password")
        ->addRule(Form::FILLED, 'Vyplňte heslo')
        ->addRule(Form::MIN_LENGTH, 'Vytvořte heslo které obsahuje minimálně %d znaků', 6)
        ->addRule(Form::MAX_LENGTH,"Heslo může mít maximálně %d znaků",15);
        
    $form->addPassword("passwordRepeat")
        ->addRule(Form::FILLED, 'Potvrďte heslo')
        ->addRule(Form::EQUAL,"Opravte prosím potvrzení hesla , hesla se neshodují",$form["password"])
        ->addRule(Form::MAX_LENGTH,"Heslo může mít maximálně %d znaků",15);
        
    $form->addSubmit("register","Registrovat");
    $form->onSuccess[] = [$this,"registrationFormSucceeded"];
    return $form;
    }
    //when form is successfully sent
    public function registrationFormSucceeded($form,array $values)
    {
        if($registrace = $this->UserManager->add($values["user"],$values["email"],$values["password"]) == TRUE)
        {
	//vse probehlo uspesne
        $this->flashMessage('Registrace proběhla úspěšně , můžete se vrátit na hlavní stranu a přihlásit se',"blesk");
        $this->redirect('Register:default');
        }
        else
        {
	//nekde nastala chyba
        $this->flashMessage("Zadané jméno nebo email již používá jiný účet","bleskNe");
        }      
    }
    
}

