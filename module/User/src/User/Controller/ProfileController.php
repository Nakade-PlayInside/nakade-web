<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for
 * the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc.
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Editing the user's own profile
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
        //redirection does not work here if the
        //protected method is provided!
        $profile = $this->identity();

        if ($profile === null) {
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel(
            array(
               'profile'    => $profile,
               'name'       => $profile->getName(),
               'username'   => $profile->getUsername(),
           )
        );
    }

    /**
     * edit the birthday
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function birthdayAction()
    {
        $profile = $this->getProfile();

        $form = $this->getForm('birthday');
        $form->bindEntity($profile);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['uid']=$this->getProfile()->getId();
                $this->getService()->editProfile($data);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array (
               'form'    => $form
            )
        );

    }

    /**
     * edit the nickname
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function nickAction()
    {
        $profile = $this->getProfile();

        $form = $this->getForm('nick');
        $form->bindEntity($profile);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['uid']=$this->getProfile()->getId();
                $this->getService()->editProfile($data);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array (
              'form'    => $form
            )
        );
    }

    /**
     * edit the KGS name
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function kgsAction()
    {
        $profile = $this->getProfile();

        $form = $this->getForm('kgs');
        $form->bindEntity($profile);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['uid']=$this->getProfile()->getId();
                $this->getService()->editProfile($data);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * edit the email
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function emailAction()
    {
        $profile = $this->getProfile();
        $form = $this->getForm('email');
        $form->bindEntity($profile);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['uid']=$this->getProfile()->getId();
                $this->getService()->editProfile($data);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );
    }

    /**
     * change the password. No email is send.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function passwordAction()
    {

        if ($this->identity() === null) {
            return $this->redirect()->toRoute('login');
        }

        $form = $this->getForm('password');
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['uid']=$this->getProfile()->getId();
                $this->getService()->editPassword($data);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );
    }

    /**
     * change the password. No email is send.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function languageAction()
    {

        if ($this->identity() === null) {
            return $this->redirect()->toRoute('login');
        }

        $form = $this->getForm('language');
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('profile');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['uid']=$this->getProfile()->getId();
                $this->getService()->editProfile($data);

                return $this->redirect()->toRoute('profile');
            }
        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * get the user's profile. Redirect to the login if not logged in.
     *
     * @return mixed
     */
    protected function getProfile()
    {
        $profile = $this->identity();

        if ($profile === null) {
            return $this->redirect()->toRoute('login');
        }

        return $profile;
    }

}
