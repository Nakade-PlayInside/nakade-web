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
class AuthFormFactory extends AbstractFormFactory
{
    const AUTH = 'auth';
    private $captcha;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Authentication\Form\AuthForm
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config     = $services->get('config');

        $textDomain = isset($config['NakadeAuth']['text_domain']) ?
            $config['NakadeAuth']['text_domain'] : null;

        $captcha    = $services->get('Nakade\Services\NakadeCaptchaFactory');
        $translator = $services->get('translator');

        $this->setTranslatorTextDomain($textDomain);
        $this->setTranslator($translator);
        $this->setCaptcha($captcha);

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
                $form = new AuthForm($this->getCaptcha());
                break; //init made by binding entity

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
