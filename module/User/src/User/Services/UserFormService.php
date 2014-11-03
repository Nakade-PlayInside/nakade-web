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
    private $couponHydrator;

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
                $form = new Form\BirthdayForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::EMAIL_FORM:
                $form = new Form\EmailForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::FORGOT_PASSWORD_FORM:
                $form = new Form\ForgotPasswordForm($this->getServices(), $this->getUserHydrator());
                $form->init();
                break;

            case self::KGS_FORM:
                $form = new Form\KgsForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::NICK_FORM:
                $form = new Form\NickForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::PASSWORD_FORM:
                $form = new Form\PasswordForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::USER_FORM:
                $form = new Form\UserForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::LANGUAGE_FORM:
                $form = new Form\LanguageForm($this->getServices(), $this->getUserHydrator());
                break;

            case self::CONFIRM_FORM:
                $form = new Form\ConfirmForm();
                break;

            case self::REGISTER_CLOSED_BETA_FORM:
                $pwdService  = $this->getServices()->get('Nakade\Services\PasswordService');
                $closedBeta = new Form\Hydrator\ClosedBetaHydrator($pwdService);
                $form = new Form\RegisterClosedBetaForm($this->getServices(), $closedBeta);
                break;

            case self::INVITE_FRIEND_FORM:
                $form = new Form\InviteFriendForm($this->getCouponHydrator());
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
     * @return ServiceLocatorInterface
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return Form\Hydrator\UserHydrator
     */
    private function getUserHydrator()
    {
        if (is_null($this->userHydrator)) {
            $pwdService  = $this->getServices()->get('Nakade\Services\PasswordService');
            $this->userHydrator = new Form\Hydrator\UserHydrator($pwdService);
        }
        return $this->userHydrator;

    }

    /**
     * @return Form\Hydrator\CouponHydrator
     */
    private function getCouponHydrator()
    {
        if (is_null($this->couponHydrator)) {

            $entityManager = $this->getServices()->get('Doctrine\ORM\EntityManager');
            $authenticationService = $this->getServices()->get('Zend\Authentication\AuthenticationService');
            $this->couponHydrator = new Form\Hydrator\CouponHydrator($entityManager, $authenticationService);
        }

        return $this->couponHydrator;

    }


}
