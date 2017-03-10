<?php
namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\GamesManager;


class ProduktPresenter extends BasePresenter
{  
   //hotovo
   //get product
   public function renderDefault($produkt)
    {
       $this->template->hra = $this->GamesManager->select("","",$produkt); 
    }
    
    //select image for galerry and select next image
    public function actionGalerie($id,$obrazek)
    {
        $this->template->id = $id;
        $this->template->obrazek = $obrazek;
        
        if($obrazek == "Nahled1")
        {
            $this->template->dalsi = "Nahled2";
        }
        if($obrazek == "Nahled2")
        {
            $this->template->dalsi = "Nahled3";
        }
        if($obrazek == "Nahled3")
        {
            $this->template->dalsi = "Nahled4";
        }
        if($obrazek == "Nahled4")
        {
            $this->template->dalsi = "Nahled1";
        }
        
        if(!file_exists("../www/images/Produkty/".$id."/".$obrazek.".jpg"))
                {
            $this->template->id = "neexistuje";
            
                }
    }

}