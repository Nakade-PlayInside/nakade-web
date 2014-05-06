<?php

namespace Appointment\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Traversable;
use Appointment\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;



/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class AppointmentFormFactory extends AbstractFormFactory
{

    const APPOINTMENT_FORM = 'appointment';
    const CONFIRM_FORM = 'confirm';
    const REJECT_FORM = 'reject';

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return AppointmentFormFactory
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;

        $translator = $services->get('translator');

        //@todo: Übersetzung der Std Validatoren
       //needed for translating validation messages
        $translator->addTranslationFile(
            'phpArray',
            'vendor/zendframework/zendframework/resources/languages/de/Zend_Validate.php',
            'default',
            'de_DE'
        );

       $this->setTranslator($translator, $textDomain);

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
               $form = new Form\AppointmentForm(4);
               break; //init made by binding entity

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
        return $form;
    }
}
