<?php

namespace Authentication\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Authentication\Form\AuthForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Captcha\AdapterInterface ;

/**
 * Class AuthFormFactory
 *
 * @package Authentication\Services
 */
class FormFactory extends AbstractFormFactory
{
    const AUTH = 'auth';
    private $captcha;
    private $session;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Authentication\Form\AuthForm
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['NakadeAuth']['text_domain']) ?
            $config['NakadeAuth']['text_domain'] : null;

        $this->captcha = $services->get('Nakade\Services\NakadeCaptchaFactory');
        $this->session = $services->get('Authentication\Services\SessionService');

        $translator = $services->get('translator');
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

            case self::AUTH:
                $form = new AuthForm($this->captcha, $this->session);
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown form type was provided.')
                );
        }

        $form->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());
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
