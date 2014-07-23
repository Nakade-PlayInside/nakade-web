<?php
namespace User\Services;

use Nakade\Abstracts\AbstractFormFactory;
use User\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserFormService
 *
 * @package User\Services
 */
class UserFormService extends AbstractFormFactory
{
    const BIRTHDAY_FORM = 'birthday';
    const EMAIL_FORM = 'email';
    const FORGOT_PASSWORD_FORM = 'forgot';
    const KGS_FORM = 'kgs';
    const NICK_FORM = 'nick';
    const PASSWORD_FORM = 'password';
    const USER_FORM = 'user';
    const LANGUAGE_FORM = 'language';

    private $fieldService;
    private $filterService;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($this->entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['User']['text_domain']) ?
            $config['User']['text_domain'] : null;

        $translator = $services->get('translator');
        $fieldSetService = $services->get('Season\Services\SeasonFieldsetService');
        $this->fieldService = $services->get('User\Services\UserFieldService');
        $this->filterService = $services->get('User\Services\UserFilterService');

        $this->setFieldSetService($fieldSetService);
        $this->setTranslator($translator, $textDomain);

       return $this;
    }

    /**
     * @param string $typ
     *
     * @return \Zend\Form\Form
     *
     * @throws \RuntimeException
     */
    public function getForm($typ)
    {
        $buttonService = $this->getFieldSetService();
        $fieldService = $this->getFieldService();
        $filterService = $this->getFilterService();

        switch (strtolower($typ)) {

           case self::BIRTHDAY_FORM:
               $form = new Form\BirthdayForm($buttonService, $fieldService, $filterService);
               break;

           case self::EMAIL_FORM:
               $form = new Form\EmailForm($buttonService, $fieldService, $filterService);
               break;

           case self::FORGOT_PASSWORD_FORM:
               $form = new Form\ForgotPasswordForm($buttonService, $fieldService, $filterService);
               break;

           case self::KGS_FORM:
               $form = new Form\KgsForm($buttonService, $fieldService, $filterService);
               break;

           case self::NICK_FORM:
               $form = new Form\NickForm($buttonService, $fieldService, $filterService);
               break;

           case self::PASSWORD_FORM:
               $form = new Form\PasswordForm($buttonService, $fieldService, $filterService);
               break;

           case self::USER_FORM:
               $form = new Form\UserForm($buttonService, $fieldService, $filterService);
               break;

            case self::LANGUAGE_FORM:
                $form = new Form\LanguageForm($buttonService, $fieldService, $filterService);
                break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->setTranslator($this->translator, $this->textDomain);
        return $form;
    }

    /**
     * @return \User\Form\Factory\UserFieldFactory
     */
    public function getFieldService()
    {
        return $this->fieldService;
    }

    /**
     * @return \User\Form\Factory\UserFilterFactory
     */
    public function getFilterService()
    {
        return $this->filterService;
    }

}
