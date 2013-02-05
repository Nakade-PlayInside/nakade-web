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
  
   protected $_blogTable;
    
   public function indexAction()
   {
        return new ViewModel(
            array(
               'blog' => $this->getBlogTable()->fetchAll(),
            )
        );
   }

   public function getBlogTable()
   {
        if (!$this->_blogTable) {
            $serviceManager = $this->getServiceLocator();
            $this->_blogTable = $serviceManager->get('Blog\Model\BlogTable');
        }
        return $this->_blogTable;
   }
    
    
}