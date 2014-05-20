<?php
namespace Nakade\Abstracts;

use Zend\Validator\AbstractValidator;

/**
 * Extend for making own Validators.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractNakadeValidator extends AbstractValidator
{

    /**
     * @return mixed
     */
    abstract public function getTranslatedTemplate();

    /**
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

       return $translator->translate($message, $this->getTranslatorTextDomain());

    }
}

