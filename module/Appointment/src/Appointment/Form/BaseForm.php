<?php
namespace Appointment\Form;

use Appointment\Entity\Appointment;
use Appointment\Form\Hydrator\AppointmentHydrator;
use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BaseForm
 *
 * @package Appointment\Form
 */
abstract class BaseForm extends AbstractForm implements AppointmentInterface
{
    protected $fieldSetService;
    protected $authenticationService;

    /**
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {

        //form name
        parent::__construct($this->getFormName());
        $this->service = $serviceManager;

        $this->fieldSetService = $serviceManager->get('Season\Services\SeasonFieldsetService');
        $this->entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
        $this->authenticationService = $serviceManager->get('Zend\Authentication\AuthenticationService');

        $hydrator = new AppointmentHydrator($this->getEntityManager(), $this->getAuthenticationService());
        $this->setHydrator($hydrator);

    }

    /**
     * @param Appointment $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);

    }

    /**
     * @return SeasonFieldsetService
     */
    public function getFieldSetService()
    {
        return $this->fieldSetService;
    }

    /**
     * @return \Season\Form\Fieldset\ButtonFieldset
     */
    public function getButtonFieldSet()
    {
        return $this->getFieldSetService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET);
    }

    /**
     * @param SeasonFieldsetService $fieldSetService
     */
    public function setFieldSetService(SeasonFieldsetService $fieldSetService)
    {
        $this->fieldSetService = $fieldSetService;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return string
     */
    abstract public function getFormName();

}

