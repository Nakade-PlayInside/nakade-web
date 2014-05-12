<?php
namespace Nakade\Abstracts;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\I18n\Translator\Translator;
use Mail\NakadeMail;


/**
 * Extending for having a service getter and setter
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class AbstractController extends AbstractActionController
{

    protected $service;
    protected $formFactory;
    protected $repository;
    protected $translator;
    protected $mailService;

    /**
     * @return mixed
     */
    public function getService()
    {
       return $this->service;
    }

    /**
     * @param mixed $service
     *
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return AbstractFormFactory
     */
    public function getFormFactory()
    {
       return $this->formFactory;
    }

    /**
     * @param AbstractFormFactory $factory
     *
     * @return $this
     */
    public function setFormFactory(AbstractFormFactory $factory)
    {
        $this->formFactory = $factory;
        return $this;
    }

    /**
     * @param string $typ
     * @param string $lang
     *
     * @return /Zend/Form/Form
     */
    public function getForm($typ, $lang=null)
    {

        if (null === $this->formFactory) {
            return null;
        }

        return $this->getFormFactory()->getForm($typ, $lang);

    }

    /**
     * @param FactoryInterface $repository
     *
     * @return $this
     */
    public function setRepository(FactoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param \Zend\I18n\Translator\Translator $translator
     *
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param FactoryInterface $mail
     *
     * @return $this
     */
    public function setMailService(FactoryInterface $mail)
    {
        $this->mailService = $mail;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getMailService()
    {
        return $this->mailService;
    }
}
