<?php

namespace Appointment\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Appointment\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AppointmentFormFactory
 *
 * @package Appointment\Services
 */
class AppointmentFormFactory extends AbstractFormFactory
{

    const APPOINTMENT_FORM = 'appointment';
    const CONFIRM_FORM = 'confirm';
    const REJECT_FORM = 'reject';

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->setServiceManager($services);
        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;

        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        return $this;
    }

    /**
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
               $form = new Form\AppointmentForm($this->getServiceManager());
               break;

           case self::CONFIRM_FORM:
               $form = new Form\ConfirmForm($this->getServiceManager());
               break;

           case self::REJECT_FORM:
               $form = new Form\RejectForm($this->getServiceManager());
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->setTranslator($this->translator, $this->textDomain);
        return $form;
    }

}
