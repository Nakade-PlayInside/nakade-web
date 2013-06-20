<?php

namespace User\Factory;

use Nakade\Abstracts\AbstractFormFactory;
use Traversable;
use User\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;


/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class FormFactory extends AbstractFormFactory
{
    
    /**
     * implemented ServiceLocator
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \User\Services\FormFactory
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //configuration 
        $textDomain = isset($config['User']['text_domain']) ? 
            $config['User']['text_domain'] : null;
        
        
        $translator = $services->get('translator');
       
       //needed for translating validation messages
        $translator->addTranslationFile( 
            'phpArray', 
            'vendor/zendframework/zendframework/resources/languages/de/Zend_Validate.php',
            'default',
            'de_DE'    
        );
        
        $this->setTranslator($translator);
        $this->setTranslatorTextDomain($textDomain);
       
        //EntityManager for database access by doctrine
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
        $this->setEntityManager($entityManager);
        
        return $this;
    }
    
    /**
     * fabric method for getting the form needed. expecting the form name as
     * string. Throws an exception if provided typ is unknown.
     * 
     * @param string $typ
     * @return Form
     * @throws RuntimeException
     */
    public function getForm($typ)
    {
       
        $entityManager = $this->getEntityManager();
        
        switch (strtolower($typ)) {
           
           case "birthday": $form = new Form\BirthdayForm();
                            break;
               
           case "email":    $form = new Form\EmailForm($entityManager);
                            break;
                        
           case "nick":     $form = new Form\NickForm($entityManager);
                            break;                          
                        
           case "password": $form = new Form\PasswordForm($entityManager);
                            break;
           
           case "user":     $form = new Form\UserForm($entityManager);
                            break;             
                        
           default      :   throw new RuntimeException(
                sprintf('An unknown form type was provided.')
           );              
        }
        
        $form->setTranslator(
            $this->getTranslator(), 
            $this->getTranslatorTextDomain()
        );
   
        return $form;
    }      
}
