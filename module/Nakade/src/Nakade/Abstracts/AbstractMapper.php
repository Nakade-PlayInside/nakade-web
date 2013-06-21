<?php
namespace Nakade\Abstracts;


use Doctrine\ORM\EntityManager;
/**
 * Extending for having the doctrine entity manager involved
 * In addition an optional translator can be set
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class AbstractMapper extends AbstractTranslation
{
   
   protected $_entity_manager; 
  
   
   /**
   * Sets the EntityManager
   *
   * @param EntityManager $entitymanager
   * @access public
   * @return ActionController
   */
   public function setEntityManager(EntityManager $em)
   {
      $this->_entity_manager = $em;
      return $this;
   }

  /**
   * Returns the EntityManager
   *
   * Fetches the EntityManager from ServiceLocator if it has not been initiated
   * and then returns it
   *
   * @access public
   * @return EntityManager
   */
   public function getEntityManager()
   {
      return $this->_entity_manager;
   }
   
   /**
   * Returns true if EntityManager is set
   *
   * @access public
   * @return bool
   */
   public function hasEntityManager()
   {
      return isset($this->_entity_manager);
   }
   
   /**
    * Returns the primary key of the given
    * entity or null if the entity manager is not set.
    * 
    * @param entity $object
    * @return null|string
    */
   public function getIdentifier($object)
   {
       if($this->hasEntityManager()) {
           return $this->getEntityManager()
                 ->getClassMetaData(get_class($object))
                 ->getSingleIdentifierFieldName();
       }
       return null;
       
   }
   
   /**
    * saves the entity. 
    * return true on success
    * 
    * @param Entity $entity
    */
   public function save($entity)
   {
       
       if($entity===null) {
           return $this->getEntityManager()->flush();
       }
    
       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);
       
   }
   
   public function update($entity)
   {
       
       if($entity===null) {
           return $this->getEntityManager()->flush();
       }
    
       $this->getEntityManager()->persist($entity); 
       $this->getEntityManager()->flush($entity);
       
   }
   
   
}

?>
