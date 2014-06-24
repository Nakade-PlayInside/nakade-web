<?php

namespace Appointment\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Appointment\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Appointment\Form\Hydrator\AppointmentHydrator;



/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class AppointmentFormFactory extends AbstractFormFactory
{

    const APPOINTMENT_FORM = 'appointment';
    const CONFIRM_FORM = 'confirm';
    const REJECT_FORM = 'reject';

    private $authenticationService;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($this->entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        $this->authenticationService = $services->get('Zend\Authentication\AuthenticationService');

        $fieldSetService = $services->get('Season\Services\SeasonFieldsetService');

        $config  = $services->get('config');

        //configuration
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;

        $translator = $services->get('translator');

        $this->setTranslator($translator);
        $this->setTranslatorTextDomain($textDomain);
        $this->setFieldSetService($fieldSetService);

       return $this;
    }

    /**
     * fabric method for getting the form needed. expecting the form name as
     * string. Throws an exception if provided typ is unknown.
     *
     * @param string $typ
     *
     * @return \Zend\Form\Form
     *
     * @throws \RuntimeException
     */
    public function getForm($typ)
    {

        switch (strtolower($typ)) {

           case self::APPOINTMENT_FORM:
               $period = $this->getMaxDatePeriod();
               $form = new Form\AppointmentForm($this->getFieldSetService());
               $hydrator = new AppointmentHydrator($this->entityManager, $this->authenticationService);
               $form->setHydrator($hydrator);
               break;

           case self::CONFIRM_FORM:
               $form = new Form\ConfirmForm();
               break;

           case self::REJECT_FORM:
               $form = new Form\RejectForm();
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->setTranslator($this->getTranslator());
        $form->setTranslatorTextDomain($this->getTranslatorTextDomain());
        return $form;
    }

    /**
     * @return int
     */
    public function getMaxDatePeriod()
    {
        return $this->maxDatePeriod;
    }
}
