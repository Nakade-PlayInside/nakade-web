<?php
namespace League\Statistics;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;

/**
 * Description of AbstractTranslatorClass
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class AbstractTranslator implements TranslatorAwareInterface
{

    protected $_translator;
    protected $_textDomain='League';

    /**
     * @param Translator $translator
     *
     * @param null $textDomain
     *
     * @return void|TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        if (isset($translator)) {
            $this->_translator=$translator;
        }

        if (isset($textDomain)) {
            $this->_textDomain=$textDomain;
        }
    }

    /**
     * Returns translator used in object
     *
     * @return Translator|null
     */
    public function getTranslator()
    {
        return $this->_translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return isset($this->_translator);
    }

    /**
     * @param bool $enabled
     *
     * @return void|TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled=true)
    {;
    }

    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled()
    {;
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
        $this->_textDomain=$textDomain;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->_textDomain;
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

