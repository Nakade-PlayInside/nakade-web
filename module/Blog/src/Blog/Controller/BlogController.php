<?php
/**
 * Controller Blog
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

// module/Blog/src/Blog/Controller/BlogController.php:
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
  
    
    public function indexAction()
    {
        return new ViewModel();
    }

   
    
    
}