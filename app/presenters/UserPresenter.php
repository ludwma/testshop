<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;

class UserPresenter extends BasePresenter {

    //check if user is logged in
    protected function startup() {
        parent::startup();
        $user = $this->getUser();
        if (!$user->isLoggedIn()) {
            $this->redirect("Nenipristup:default");
        }

        //get info about user
        $this->template->userinfo = $this->UserManager->getUserInfo($this->user->identity->data["user_id"]);
    }

    //user log out
    public function actionOdhlasit() {
        $this->user->logout();
        $this->flashMessage("Odhlášení proběhlo úspěšně", "blesk");
        $this->redirect("Homepage:");
    }

    //edit user 
    public function createComponentZmenUdajeForm() {
        $form = new Form;
        $form->addText("user")
                ->addRule(Form::FILLED, 'Vyplňte prosím vaše uživatelské jméno')
                ->addRule(Form::MAX_LENGTH, "Uživatelské jméno může mít maximálně %d znaků", 15);

        $form->addEmail("email")
                ->addRule(Form::FILLED, 'Vyplňte váš email')
                ->addRule(Form::EMAIL, 'Opravte prosím chybu v emailové adrese')
                ->addRule(Form::MAX_LENGTH, "Email může mít maximálně %d znaků", 30);

        $form->addPassword("Apassword")
                ->addRule(Form::FILLED, 'Pro změnu ůdajů prosím vyplňte vaše aktuální heslo')
                ->addRule(Form::MAX_LENGTH, "Heslo může mít maximálně %d znaků", 15);

        $form->addPassword("Npassword")
                ->setRequired(FALSE)
                ->addRule(Form::MIN_LENGTH, 'Vytvořte heslo které obsahuje minimálně %d znaků', 6)
                ->addRule(Form::MAX_LENGTH, "Heslo může mít maximálně %d znaků", 15);

        $form->addPassword("passwordRepeat")
                ->setRequired(FALSE)
                ->addRule(Form::EQUAL, "Opravte prosím potvrzení , hesla se neshodují", $form["Npassword"])
                ->addRule(Form::MAX_LENGTH, "Heslo může mít maximálně %d znaků", 15);

        $form->addSubmit("odeslat");

        $form->onSuccess[] = function($form, $values) {
            if (password_verify($values["Apassword"], $this->UserManager->getUserInfo($this->user->identity->data["user_id"])["password"])) {
                //user dont want change password
                if (!$values["Npassword"]) {
                    if ($this->UserManager->editUser($values["user"], $values["email"], $values["Apassword"], $this->user->identity->data["user_id"], $this->user->identity->data["username"], $this->user->identity->data["email"]) == TRUE) {
                    //user want change password
                        $this->flashMessage("Údaje byly úspěšně změněny", "blesk");
                        $this->user->logout();
                        $this->user->login($values["user"], $values["Apassword"]);
                        $this->redirect("User:default");
                    }
                    //name or email already use another user
                    else {
                        $this->flashMessage("Zadané jméno nebo email již používá někdo jiný , údaje nebyli změněny", "bleskNe");
                    }
                } else {
                //user want change password
                    if ($this->UserManager->editUser($values["user"], $values["email"], $values["Npassword"], $this->user->identity->data["user_id"], $this->user->identity->data["username"], $this->user->identity->data["email"]) == TRUE) {
                    //values have been changed
                        $this->flashMessage("Údaje byly úspěšně změněny", "blesk");
                        $this->user->logout();
                        $this->user->login($values["user"], $values["Npassword"]);
                        $this->redirect("User:default");
                    }
                    //name or email already use another user
                    else {
                        $this->flashMessage("Zadané jméno nebo email již používá někdo jiný , údaje nebyli změněny", "bleskNe");
                    }
                }
            }
            //password verification failed
            else {
                $this->flashMessage("Zadal/a jste špatné aktuální heslo", "bleskNe");
            }
        };

        return $form;
    }

}
