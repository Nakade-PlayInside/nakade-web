<?php

// module/Kontakt/src/Kontakt/Controller/KontaktController.php:
namespace Kontakt\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class KontaktController extends AbstractActionController
{
  
    
    public function indexAction()
    {
        return new ViewModel();
    }

   
    
    
}