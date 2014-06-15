<?php
namespace Nakade\Abstracts;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\Form\Fieldset;

/**
 * Class AbstractFieldset
 *
 * @package Nakade\Abstracts
 */
abstract class AbstractFieldset extends Fieldset
{

    protected $translator;
    protected $textDomain="default";

    /**
     * @return array
     */
    abstract public function getInputFilterSpecification();


    /**
     * @param Translator $translator
     *
     * @param string     $textDomain
     *
     * @return Translator|TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        if (isset($translator)) {
            $this->translator=$translator;
        }

        if (isset($textDomain)) {
            $this->textDomain=$textDomain;
        }
        return $translator;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return !empty($this->translator);
    }

    /**
     * Set translation text domain
     *
     * @param string $textDomain
     *
     * @return $this
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->textDomain=$textDomain;
        return $this;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->textDomain;
    }

    /**
    * Helper for i18N. If a translator is set to the controller, the
    * message is translated.
    *
    * @param string $message
     *
    * @return string
    */
   public function translate($message)
   {

       $translator = $this->getTranslator();
       if (!$this->hasTranslator()) {
           return $message;
       }

       return $translator->translate(
           $message,
           $this->getTranslatorTextDomain()
       );

   }



}

