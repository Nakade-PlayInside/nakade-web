<?php

namespace Application\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Nakade\FormServiceInterface;
use Traversable;
use Application\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Captcha\AdapterInterface ;


/**
 * Class ContactFormFactory
 *
 * @package Application\Services
 */
class ContactFormFactory extends AbstractFormFactory
{

    const CONTACT_FORM = 'contact';
    private $captcha;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
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
        $captcha = $services->get('Application\Services\ContactCaptchaFactory');

        $this->setCaptcha($captcha);
        $this->setTranslator($translator);
        $this->setTranslatorTextDomain($textDomain);

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

           case self::CONTACT_FORM:
               $form = new Form\ContactForm($this->getCaptcha());
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->setTranslator($this->getTranslator());
        $form->setTranslatorTextDomain($this->getTranslatorTextDomain());
        $form->init();
        return $form;
    }

    /**
     * @param AdapterInterface $captcha
     *
     * @return $this
     */
    public function setCaptcha(AdapterInterface $captcha)
    {
        $this->captcha = $captcha;
        return $this;
    }

    /**
     * @return AdapterInterface
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

}
