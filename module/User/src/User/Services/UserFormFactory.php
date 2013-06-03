<?php

namespace User\Services;

use Traversable;
use User\Form\Fieldset\ProfileFieldset;
use User\Form\Fieldset\CredentialsFieldset;
use User\Form\UserForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;

/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class UserFormFactory implements FactoryInterface
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
        
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
       
        $fieldsets = array();
        $fieldsets[] = new ProfileFieldset($entityManager);
        $fieldsets[] = new CredentialsFieldset($entityManager);
      
        $form    = new UserForm($fieldsets);
        $form->setTranslator($translator, $textDomain);
        
        //EntityManager for database access by doctrine
        
       
        //set result types
      
        
        
        return $form;
    }
}
