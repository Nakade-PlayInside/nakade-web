<?php
namespace Season\Controller\Plugin;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AbstractEntityPlugin extends AbstractPlugin
{

    /**
   * @var EntityManager
   */
   protected $_entityManager;

   /**
   * Sets the EntityManager
   *
   * @param EntityManager $entitymanager
   *
   * @return ActionController
   */

   protected function setEntityManager(EntityManager $entitymanager)
   {

      $this->_entityManager = $entitymanager;
      return $this;
   }

  /**
   * Returns the EntityManager
   *
   * Fetches the EntityManager from ServiceLocator if it has not been initiated
   * and then returns it
   *
   * @return EntityManager
   */
   protected function getEntityManager()
   {
      if (null === $this->_entityManager) {

         $this->setEntityManager(
             $this->getController()
                  ->getServiceLocator()
                  ->get('Doctrine\ORM\EntityManager')
         );

      }
      return $this->_entityManager;
   }

   /**
    * saves the entity.
    *
    * @param Entity $entity
    */
    public function save($entity)
    {
       if ($entity===null) {
           return $this->getEntityManager()->flush();

       }

       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);
    }

}

?>
