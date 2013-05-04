<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for 
 * the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. 
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
   
    public function indexAction()
    {
        $blogWidget = $this->forward()->dispatch('/blog/controller/blog');
        $tableWidget = $this->forward()->dispatch('/league/controller/table');
        
        
        $page = new ViewModel();
        $page->addChild($blogWidget, 'blogWidget');
        $page->addChild($tableWidget, 'tableWidget');
        
        return $page;
       
    }
    
}
