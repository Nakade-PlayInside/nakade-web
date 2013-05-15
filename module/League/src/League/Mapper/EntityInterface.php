<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
interface EntityInterface {
   
    /**
   * Sets the EntityManager
   *
   * @param EntityManager $entitymanager
   * @access protected
   * @return ActionController
   */
   public function setEntityManager($em);

  /**
   * Returns the EntityManager
   *
   * Fetches the EntityManager from ServiceLocator if it has not been initiated
   * and then returns it
   *
   * @access protected
   * @return EntityManager
   */
   public function getEntityManager();
   
   /**
    * saves the entity. 
    * 
    * @param Entity $entity
    */
   public function save($entity);
   
}

?>
