<?php
/**
 * Controller Privacy
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

// module/Privacy/src/Privacy/Controller/PrivacyController.php:
namespace Privacy\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PrivacyController extends AbstractActionController
{
  
    
    public function indexAction()
    {
        return new ViewModel();
    }

   
    
    
}