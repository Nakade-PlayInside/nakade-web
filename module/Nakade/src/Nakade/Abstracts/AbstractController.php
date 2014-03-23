<?php
namespace Nakade\Abstracts;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\FactoryInterface;

/**
 * Extending for having a service getter and setter
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class AbstractController extends AbstractActionController
{

    protected $_service;
    protected $_formFactory;
    protected $_repository;

    /**
     * @return mixed
     */
    public function getService()
    {
       return $this->_service;
    }

    /**
     * @param mixed $service
     *
     * @return $this
     */
    public function setService($service)
    {
        $this->_service = $service;
        return $this;
    }

    /**
     * @return AbstractFormFactory
     */
    public function getFormFactory()
    {
       return $this->_formFactory;
    }

    /**
     * @param AbstractFormFactory $factory
     *
     * @return $this
     */
    public function setFormFactory(AbstractFormFactory $factory)
    {
        $this->_formFactory = $factory;
        return $this;
    }

    /**
     * @param string $typ
     *
     * @return null
     */
    public function getForm($typ)
    {

        if (null === $this->_formFactory) {
            return null;
        }

        return $this->_formFactory->getForm($typ);

    }

    /**
     * @param FactoryInterface $repository
     *
     * @return $this
     */
    public function setRepository(FactoryInterface $repository)
    {
        $this->_repository = $repository;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getRepository()
    {
        return $this->_repository;
    }
}

?>
