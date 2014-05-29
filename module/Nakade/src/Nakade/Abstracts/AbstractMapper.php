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

   protected $entityManager;

   /**
   * Sets the EntityManager
   *
   * @param EntityManager $em
   *
   * @return $this
   */
   public function setEntityManager(EntityManager $em)
   {
      $this->entityManager = $em;
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
      return $this->entityManager;
   }

   /**
   * Returns true if EntityManager is set
   *
   * @access public
   * @return bool
   */
   public function hasEntityManager()
   {
      return isset($this->entityManager);
   }

   /**
    * Returns the primary key of the given
    * entity or null if the entity manager is not set.
    *
    * @param object $object
    *
    * @return null|string
    */
   public function getIdentifier($object)
   {
       if ($this->hasEntityManager()) {
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
    * @param object $entity
    */
   public function save($entity)
   {

       if (is_null($entity)) {
           $this->getEntityManager()->flush();
           return;
       }
       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);

   }

    /**
     * @param object $entity
     */
   public function update($entity)
   {

       if (is_null($entity)) {
           $this->getEntityManager()->flush();
           return;
       }

       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);

   }
}
