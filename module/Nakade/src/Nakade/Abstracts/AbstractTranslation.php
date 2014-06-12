<?php
namespace Nakade\Abstracts;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;


/**
 * Extend this class for having an optional translator implemented.
 * Use the translate method for all text. If no translator is set,
 * the method will return the original message.
 *
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class AbstractTranslation implements TranslatorAwareInterface
{

    protected $translatorEnabled=true;
    protected $translator;
    protected $textDomain='default';

    /**
     * Sets translator to use in helper
     *
     * @param Translator $translator
     * @param string     $textDomain
     *
     * @return TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        if (isset($translator)) {
            $this->translator=$translator;
        }


        if (isset($textDomain)) {
            $this->textDomain=$textDomain;
        }

    }

    /**
     * Returns translator used in object
     *
     * @return Translator|null
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
        return isset($this->translator);
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setTranslatorEnabled($enabled=true)
    {
        $this->translatorEnabled=$enabled;
        return $this;
    }

    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled()
    {
        return $this->translatorEnabled;
    }

    /**
     * Set translation text domain
     *
     * @param string $textDomain
     *
     * @return TranslatorAwareInterface
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->textDomain=$textDomain;
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
       if (is_null($translator)) {
           return $message;
       }

       return $translator->translate(
           $message,
           $this->getTranslatorTextDomain()
       );

   }


}


