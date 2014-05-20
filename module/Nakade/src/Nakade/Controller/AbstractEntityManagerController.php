<?php
/**
 * Nakade Abstract based on Zend (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source
 * repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc.
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Nakade\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Doctrine 2 EntityManaging Controller
 *
 * The EntityManager is the glue between EntityModels and database. The service
 * depends on DoctrineORMModule for ZF2 which has to be registered in the
 * module.congif.php as well as the namespace in the application.config.php
 * details in (https://github.com/doctrine/DoctrineORMModule/)
 */
abstract class AbstractEntityManagerController extends AbstractActionController
{
   /**
   * @var EntityManager
   * @access protected
   */
   protected $_entityManager;

   /**
   * @param EntityManager $entityManager
   *
   * @return $this
   */

   protected function setEntityManager(EntityManager $entityManager)
   {

      $this->_entityManager = $entityManager;
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

      if (is_null($this->_entityManager)) {

         $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
         $this->setEntityManager($em);

      }

      return $this->_entityManager;
   }

}

