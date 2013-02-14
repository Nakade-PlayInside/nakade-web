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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    protected $_userTable;
    
    /**
   * @var EntityManager
   */
   protected $_entityManager;
    
    public function indexAction()
    {
        
        $repository = $this->getEntityManager()->getRepository(
            'User\Entity\User'
        );
        $posts      = $repository->findAll();
        
        return new ViewModel(
            array(
                
              'users' => $repository->findAll(),
             // 'users' => $this->getUserTable()->fetchAll(),
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
    
    public function getUserTable()
    {
        if (!$this->_userTable) {
            $servman = $this->getServiceLocator();
            $this->_userTable = $servman->get('User\Model\UserTable');
        }
        return $this->_userTable;
    }
    
    /**
   * Sets the EntityManager
   *
   * @param EntityManager $em
   * @access protected
   * @return UserController
   */
   protected function setEntityManager($em)
   {
       
      $this->_entityManager = $em;
      return $this;
   }

  /**
   * Returns the EntityManager
   *
   * Fetches the EntityManager from ServiceLocator if it has not been initiated
   * and then returns it
   *
   * @access protected
   * @return EntityManager
   */
   protected function getEntityManager()
   {
      
      if (null === $this->_entityManager) {
         
          
         $em =  $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
         $this->setEntityManager($em);
         return $em;
      }
      
      return $this->_entityManager;
   }
    
}
