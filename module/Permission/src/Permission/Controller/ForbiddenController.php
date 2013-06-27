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

namespace Permission\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Error controller for showing 403 error
 */
class ForbiddenController extends AbstractActionController
{
   
    public function indexAction()
    {
       
         return new ViewModel(
            array()
        );
    }
    
}
