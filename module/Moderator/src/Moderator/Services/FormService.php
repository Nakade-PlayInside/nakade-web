<?php

namespace Moderator\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Moderator\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FormService
 *
 * @package Moderator\Services
 */
class FormService extends AbstractFormFactory
{

    const MANAGER_FORM = 'manager';
    const SUPPORT_FORM = 'support';
    const MAIL_FORM = 'mail';
    const REFEREE_FORM = 'referee';

    private $services;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->services = $services;
        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Moderator']['text_domain']) ?
            $config['Moderator']['text_domain'] : null;

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

            case self::MANAGER_FORM:
                $form = new Form\LeagueManagerForm($this->getServices());
                $form->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());
                break;

            case self::SUPPORT_FORM:
                $form = new Form\SupportForm($this->getServices());
                $form->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());
                break;

            case self::MAIL_FORM:
                $form = new Form\MailForm($this->getServices());
                $form->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());
                break;

            case self::REFEREE_FORM:
                $form = new Form\RefereeForm($this->getServices());
                $form->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown form type was provided.')
                );
        }
        $form->setTranslator($this->translator, $this->textDomain);
        return $form;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServices()
    {
        return $this->services;
    }

}
