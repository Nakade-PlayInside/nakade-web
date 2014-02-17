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
   
    abstract public function getTranslatedTemplate();

    /**
    * Helper for i18N. If a translator is set to the controller, the 
    * message is translated.
    *  
    * @param string $message
    * @return string
    */
   public function translate($message) 
   {
   
       $translator = $this->getTranslator();
       if ($translator===null)
           return $message;
       
       return $translator->translate(
                  $message, 
                  $this->getTranslatorTextDomain()
              );
       
   }
}

?>
