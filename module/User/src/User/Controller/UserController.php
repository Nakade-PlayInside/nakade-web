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

namespace User\Controller;

use Nakade\Controller\AbstractEntityManagerController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractEntityManagerController
{
    
    public function indexAction()
    {
        
        $repository = $this->getEntityManager()->getRepository(
            'User\Entity\User'
        );
       
        
        return new ViewModel(
            array(
              'users' => $repository->findAll(),
            )
        );
    }
    
    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
    
    
}
