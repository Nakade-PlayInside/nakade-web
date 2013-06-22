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
       
        $this->setTranslator($translator, $textDomain);
        
        //EntityManager for database access by doctrine
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
        $this->setEntityManager($entityManager);
        
        return $this;
    }
    
    /**
     * fabric method for getting the form needed. expecting the form name as
     * string. Throws an exception if provided typ is unknown.
     * Typ:  - 'birthday'   => profile
     *       - 'email'      => profile
     *       - 'nick'       => profile
     *       - 'password'   => profile
     *       - 'user'       => adminstration 
     * 
     * @param string $typ
     * @return Form
     * @throws RuntimeException
     */
    public function getForm($typ)
    {
       
        
        
        switch (strtolower($typ)) {
           
           case "birthday": $form = new Form\BirthdayForm();
                            break;
               
           case "email":    $form = new Form\EmailForm();
                            break;
                        
           case "nick":     $form = new Form\NickForm();
                            break;                          
                        
           case "password": $form = new Form\PasswordForm();
                            break;
           
           case "user":     $form = new Form\UserForm();
                            break;             
                        
           default      :   throw new RuntimeException(
                sprintf('An unknown form type was provided.')
           );              
        }
        
        //em 
        $entityManager = $this->getEntityManager();
        $form->setEntityManager($entityManager);
   
        //translator
        $form->setTranslator(
            $this->getTranslator(), 
            $this->getTranslatorTextDomain()
        );
        
        //init + filter
        $form->init();
        $form->setInputFilter($form->getFilter());
        
        return $form;
    }      
}
