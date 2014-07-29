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
    const CONFIRM_FORM = 'confirm';
    const REGISTER_CLOSED_BETA_FORM = 'register_closed_beta';
    const INVITE_FRIEND_FORM = 'invite_friend';

    private $services;
    private $userHydrator;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->services = $services;
        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['User']['text_domain']) ?
            $config['User']['text_domain'] : null;

        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        $pwdService  = $services->get('Nakade\Services\PasswordService');
        $this->userHydrator = new Form\Hydrator\UserHydrator($pwdService);

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

        switch (strtolower($typ)) {

            case self::BIRTHDAY_FORM:
                $form = new Form\BirthdayForm($this->services, $this->userHydrator);
                break;

            case self::EMAIL_FORM:
                $form = new Form\EmailForm($this->services, $this->userHydrator);
                break;

            case self::FORGOT_PASSWORD_FORM:
                $form = new Form\ForgotPasswordForm($this->services, $this->userHydrator);
                $form->init();
                break;

            case self::KGS_FORM:
                $form = new Form\KgsForm($this->services, $this->userHydrator);
                break;

            case self::NICK_FORM:
                $form = new Form\NickForm($this->services, $this->userHydrator);
                break;

            case self::PASSWORD_FORM:
                $form = new Form\PasswordForm($this->services, $this->userHydrator);
                break;

            case self::USER_FORM:
                $form = new Form\UserForm($this->services, $this->userHydrator);
                break;

            case self::LANGUAGE_FORM:
                $form = new Form\LanguageForm($this->services, $this->userHydrator);
                break;

            case self::CONFIRM_FORM:
                $form = new Form\ConfirmForm();
                break;

            case self::REGISTER_CLOSED_BETA_FORM:
                $form = new Form\RegisterClosedBetaForm($this->services, $this->userHydrator);
                break;

            case self::INVITE_FRIEND_FORM:
                $form = new Form\InviteFriendForm($this->services, $this->userHydrator);
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
