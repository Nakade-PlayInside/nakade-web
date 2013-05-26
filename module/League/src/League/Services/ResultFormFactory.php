<?php

namespace League\Services;

use Traversable;
use League\Statistics\Results;
use League\Form\ResultForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;

/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class ResultFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //configuration 
        $textDomain = isset($config['League']['text_domain']) ? 
            $config['League']['text_domain'] : null;
        
        $translator = $services->get('translator');
       
       //needed for translating validation messages
        $translator->addTranslationFile( 
            'phpArray', 
            'vendor/zendframework/zendframework/resources/languages/de/Zend_Validate.php',
            'default',
            'de_DE'    
        );
        AbstractValidator::setDefaultTranslator($translator);
        $form    = new ResultForm();
        $form->setTranslator($translator, $textDomain);
        
       
        //result types using i18N
        $results  = new Results();
        $results->setTranslator($translator, $textDomain);
        
        //set result types
        $form->setResultlist($results->getResultTypes());
        
        
        return $form;
    }
}
