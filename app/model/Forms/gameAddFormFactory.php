<?php

namespace App\Model;

use Nette;
use Nette\Application\UI\Form;

class gameAddFormFactory {

    use Nette\SmartObject;

    //form for add game
    public static function create() {
        $form = new Form;
        $form->addText("jmeno")
                ->addRule(Form::FILLED, 'Vyplňte prosím jméno hry');

        $form->addTextArea("popis")
                ->addRule(Form::FILLED, 'Vyplňte popis hry');

        $form->addText("cena")
                ->setType("number")
                ->addRule(Form::FILLED, 'Zadejte prosím cenu hry');

        $zanry = [
            "akcni" => "Akční",
            "adventura" => "Adventura",
            "rpg" => "Rpg",
            "sportovni" => "Sportovní",
            "simulator" => "Simulátor",
            "plosinovka" => "Plošinovka",
            "strategie" => "Strategie",
        ];

        $form->addSelect("zanr", "Žánr", $zanry)
                ->setPrompt("Zvolte žánr")
                ->addRule(Form::FILLED, 'Vyberte prosím žánr hry');

        $verze = [
            "Pc" => "Pc",
            "Ps4" => "Ps4",
            "Ps3" => "Ps3",
            "Nintendo" => "Nintendo",
        ];

        $form->addSelect("verze", "verze", $verze)
                ->setPrompt("Zvolte verzi")
                ->addRule(Form::FILLED, 'Vyberte prosím verzi hry');

        $jazyk = [
            "Cz" => "Čeština",
            "Anglicky" => "Angličtina",
        ];

        $form->addSelect("jazyk", "jazyk", $jazyk)
                ->setPrompt("Zvolte jazyk")
                ->addRule(Form::FILLED, 'Vyberte prosím jazyk hry');

        $vydani = [
            "krabice" => "Krabicové",
            "Steam" => "Steam",
        ];

        $form->addSelect("vydani", "vydani", $vydani)
                ->setPrompt("Zvolte vydání")
                ->addRule(Form::FILLED, 'Vyberte prosím vydání hry');

        $form->addText("skladem")
                ->setType("number")
                ->addRule(Form::FILLED, 'Zadejte kolik kusů je skladem');

        $form->addText("datum")
                ->setType("date")
                ->addRule(Form::FILLED, 'Zvolte prosím datum vydání hry');

        $form->addText("ytid")
                ->addRule(Form::FILLED, 'Zadejte prosím kód pro vložení videa z youtube');

        $form->addUpload("krabice")
                ->setRequired("Nahrajte prosím obrázek krabice")
                ->addRule(Form::IMAGE, "Úvodní obrázek musí být obrázek")
                ->addRule(Form::MIME_TYPE, 'Úvodní obrázek musí být jpg', 'image/jpeg');
        $form->addUpload("nahled1")
                ->setRequired("Nahrajte prosím 1. náhledový obrázek")
                ->addRule(Form::IMAGE, "Náhled1 musí být obrázek")
                ->addRule(Form::MIME_TYPE, 'Náhled1 musí být jpg', 'image/jpeg');
        $form->addUpload("nahled2")
                ->setRequired("Nahrajte prosím 2. náhledový obrázek")
                ->addRule(Form::IMAGE, "Náhled2 musí být obrázek")
                ->addRule(Form::MIME_TYPE, 'Náhled2 musí být jpg', 'image/jpeg');
    
        $form->addUpload("nahled3")
                ->setRequired("Nahrajte prosím 3. náhledový obrázek")
                ->addRule(Form::IMAGE, "Náhled3 musí být obrázek")
                ->addRule(Form::MIME_TYPE, 'Náhled3 musí být jpg', 'image/jpeg');
     
        $form->addUpload("nahled4")
                ->setRequired("Nahrajte prosím 4. náhledový obrázek")
                ->addRule(Form::IMAGE, "Náhled4 musí být obrázek")
                ->addRule(Form::MIME_TYPE, 'Náhled4 musí být jpg', 'image/jpeg');

        $form->addSubmit("pridat", "Pridat");
        
        return $form;
    }

}
