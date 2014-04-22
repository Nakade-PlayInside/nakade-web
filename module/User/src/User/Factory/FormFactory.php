<?php

namespace User\Factory;

use Nakade\Abstracts\AbstractFormFactory;
use Traversable;
use User\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class FormFactory
 *
 * @package User\Factory
 */
class FormFactory extends AbstractFormFactory
{

    /**
     * implemented ServiceLocator
     *
     * @param ServiceLocatorInterface $services
     *
     * @return $this
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

        //@todo: Übersetzung der Std Validatoren

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
     * @param string      $typ
     * @param string|null $language
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getForm($typ, $language=null)
    {

        switch (strtolower($typ)) {

           case "birthday":
               $form = new Form\BirthdayForm();
               break;

           case "email":
               $form = new Form\EmailForm();
               break;

           case "nick":
               $form = new Form\NickForm();
               break;

           case "kgs":
               $form = new Form\KgsForm();
               break;

           case "password":
               $form = new Form\PasswordForm();
               break;

           case "user":
               $form = new Form\UserForm();
               break;

           case "forgot":
               $form = new Form\ForgotPasswordForm();
               break;

           case "language":
               $form = new Form\LanguageForm();
               $form->setLanguage($language);
               break;


           default:
               throw new \RuntimeException(
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
