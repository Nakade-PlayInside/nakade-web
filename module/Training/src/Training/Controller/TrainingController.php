<?php
/**
 * Controller Training
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

// module/Training/src/Training/Controller/TrainingController.php:
namespace Training\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TrainingController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    
}