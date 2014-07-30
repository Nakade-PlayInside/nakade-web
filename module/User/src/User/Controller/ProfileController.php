<?php
namespace User\Controller;

use User\Entity\Coupon;
use User\Services\MailService;
use User\Services\RepositoryService;
use User\Services\UserFormService;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class ProfileController
 *
 * @package User\Controller
 */
class ProfileController extends AbstractController
{

    /**
     * Showing the user's profile
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return new ViewModel(
            array(
               'profile'    => $user,
               'name'       => $user->getName(),
               'username'   => $user->getUsername(),
           )
        );
    }

    /**
     * edit the birthday
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function inviteAction()
    {
        /* @var $form \User\Form\BirthdayForm */
        $form = $this->getForm(UserFormService::INVITE_FRIEND_FORM);
        $coupon = new Coupon();
        $form->bindEntity($coupon);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $coupon \User\Entity\Coupon */
                $coupon = $form->getData();
                $this->getUserMapper()->save($coupon);

                /* @var $mail \User\Mail\CouponMail */
                $mail = $this->getMailService()->getMail(MailService::COUPON_MAIL);
                $mail->setCoupon($coupon);
                $mail->sendMail($coupon);

                $this->flashMessenger()->addSuccessMessage('Your Invitation Is Send');
                $coupon = new Coupon();
                $form->bind($coupon);

            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

        return new ViewModel(
            array('form' => $form)
        );

    }

    /**
     * edit the birthday
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function birthdayAction()
    {
        /* @var $form \User\Form\BirthdayForm */
        $form = $this->getForm(UserFormService::BIRTHDAY_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        return $this->updateProfile($request, $form);

    }

    /**
     * edit the nickname
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function nickAction()
    {
        /* @var $form \User\Form\NickForm */
        $form = $this->getForm(UserFormService::NICK_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        return $this->updateProfile($request, $form);
    }

    /**
     * edit the KGS name
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function kgsAction()
    {
        /* @var $form \User\Form\KgsForm */
        $form = $this->getForm(UserFormService::KGS_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        return $this->updateProfile($request, $form);
    }

    /**
     * edit the email
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function emailAction()
    {
        /* @var $form \User\Form\EmailForm */
        $form = $this->getForm(UserFormService::EMAIL_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        return $this->updateProfile($request, $form);
    }

    /**
     * change the password. No email is send.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function passwordAction()
    {

        /* @var $form \User\Form\PasswordForm */
        $form = $this->getForm(UserFormService::PASSWORD_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        return $this->updateProfile($request, $form);
    }

    /**
     * change the password. No email is send.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function languageAction()
    {

        /* @var $form \User\Form\LanguageForm */
        $form = $this->getForm(UserFormService::LANGUAGE_FORM);
        $user = $this->getUser();
        $form->bindEntity($user);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        return $this->updateProfile($request, $form);
    }

    /**
     * @param Request $request
     * @param Form    $form
     *
     * @return \Zend\Http\Response
     */
    private function updateProfile(Request $request, Form $form)
    {
        if ($request->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $user \User\Entity\User */
                $user = $form->getData();
                $this->getUserMapper()->save($user);

                //updating actual language
                $profile = $this->identity();
                $profile->setLanguage($user->getLanguage());

                //email form contains hidden field used for hydration
                if (isset($postData['isNewEmail'])) {

                    /* @var $mail \User\Mail\VerifyMail */
                    $mail = $this->getMailService()->getMail(MailService::VERIFY_MAIL);
                    $mail->setUser($user);
                    $mail->sendMail($user);
                }

                $this->flashMessenger()->addSuccessMessage('Profile updated');
                return $this->redirect()->toRoute('profile');
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

        return new ViewModel(
            array('form' => $form)
        );
    }

    /**
     * @return \User\Mapper\UserMapper
     */
    private function getUserMapper()
    {
        /* @var $repo \User\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::USER_MAPPER);
    }

    /**
     * @return \User\Entity\User
     */
    private function getUser()
    {
        $profile = $this->identity();
        return $this->getUserMapper()->getUserById($profile->getId());
    }

}
