<?php

namespace Nakade\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class NakadeTranslationService
 *
 * @package Nakade\Services
 */
class NakadeTranslationService extends NakadeBaseService
{
    protected $translator;
    protected $config;
    protected $textDomain;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        if (is_null($this->config)) {
            $this->config = $this->getServices()->get('config');
        }
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getTextDomain()
    {
        if (is_null($this->textDomain)) {
            $config  = $this->getConfig();
            if (isset($config['User']['text_domain'])) {
                $this->textDomain = $config['User']['text_domain'];
            }
        }
        return $this->textDomain;
    }

    /**
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator()
    {
           if (is_null($this->translator) && $this->getServices()->has('translator')) {
                $this->translator = $this->getServices()->get('translator');
           }
           return $this->translator;
    }

}
