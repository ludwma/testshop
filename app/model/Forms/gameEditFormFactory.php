<?php

namespace App\Model;

use Nette;
use Nette\Application\UI\Form;

class gameEditFormFactory {

    use Nette\SmartObject;

    //form for edit game
    public static function create($hodnoty) {
        $form = new Form;
        $form->addText("jmeno")
                ->setDefaultValue($hodnoty->jmeno)
                ->addRule(Form::FILLED, 'Vyplňte prosím jméno hry');

        $form->addTextArea("popis")
                ->setDefaultValue($hodnoty->popis)
                ->addRule(Form::FILLED, 'Vyplňte popis hry');

        $form->addText("cena")
                ->setDefaultValue($hodnoty->cena)
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
                ->setDefaultValue($hodnoty->zanr)
                ->setPrompt("Zvolte žánr")
                ->addRule(Form::FILLED, 'Vyberte prosím žánr hry');

        $verze = [
            "Pc" => "Pc",
            "Ps4" => "Ps4",
            "Ps3" => "Ps3",
            "Nintendo" => "Nintendo",
        ];

        $form->addSelect("verze", "verze", $verze)
                ->setDefaultValue($hodnoty->verze)
                ->setPrompt("Zvolte verzi")
                ->addRule(Form::FILLED, 'Vyberte prosím verzi hry');

        $jazyk = [
            "Cz" => "Čeština",
            "Anglicky" => "Angličtina",
        ];

        $form->addSelect("jazyk", "jazyk", $jazyk)
                ->setDefaultValue($hodnoty->jazyk)
                ->setPrompt("Zvolte jazyk")
                ->addRule(Form::FILLED, 'Vyberte prosím jazyk hry');

        $vydani = [
            "krabice" => "Krabicové",
            "Steam" => "Steam",
        ];

        $form->addSelect("vydani", "vydani", $vydani)
                ->setDefaultValue($hodnoty->vydani)
                ->setPrompt("Zvolte vydání")
                ->addRule(Form::FILLED, 'Vyberte prosím vydání hry');

        $form->addText("skladem")
                ->setDefaultValue($hodnoty->skladem)
                ->setType("number")
                ->addRule(Form::FILLED, 'Zadejte kolik kusů je skladem');

        $form->addText("datum")
                ->setType("date")
                ->addRule(Form::FILLED, 'Zvolte prosím datum vydání hry')
                ->setDefaultValue(date_format($hodnoty->vyslo, "d. m. Y")); //tadyto mi to nechce zkousnout
        $form->addText("ytid")
                ->setDefaultValue($hodnoty->idyt)
                ->addRule(Form::FILLED, 'Zadejte prosím kód pro vložení videa z youtube');

        $form->addSubmit("pridat", "Pridat");
        return $form;
    }

}
