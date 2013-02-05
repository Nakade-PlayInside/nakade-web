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
    private $_blogTable=false;
    
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
            $this->_blogTable = 
                $serviceManager->get('Application\Model\BlogTable');
        }
        return $this->_blogTable;
    }
    
}
