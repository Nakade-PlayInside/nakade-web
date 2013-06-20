<?php
namespace Nakade\Abstracts;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Extending for having the mail transport system and default message body.
 * In addition an optional translator can be set
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractService 
    extends AbstractTranslation 
    implements FactoryInterface
{
   
    protected $_mapper;
    protected $_mailFactory;
    
    /**
     * get a mapper for DB interaction
     * @return object
     */
    public function getMapper() 
    {
        return $this->_mapper;
    }
   
    /**
     * set a mapper for DB interaction
     * @param object $mapper
     * @return \Nakade\Abstracts\AbstractService
     */
    public function setMapper($mapper) 
    {
        $this->_mapper=$mapper;
        return $this;
    }
    
    /**
     * get an instance of a mail Factory
     * @return object
     */
    public function getMailFactory() 
    {
        return $this->_mailFactory;
    }
    
    /**
     * set an instance of a mail factory
     * @param objetc $factory
     * @return \Nakade\Abstracts\AbstractService
     */
    public function setMailFactory($factory) 
    {
        $this->_mailFactory=$factory;
        return $this;
    }
    
    /**
     * you have to implement this
     */
    abstract public function createService(ServiceLocatorInterface $services);
}

?>
