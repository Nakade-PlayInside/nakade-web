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

use Season\Services\RepositoryService;
use User\Services\UserFormService;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Adminstrative user action for adding a new user, edit and resetting pwd.
 *
 */
class UserController extends AbstractController
{

    /**
     * shows all users for adding and editing
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel(
            array(
              'users' => $this->getUserMapper()->getAllUser()
            )
        );
    }

    /**
     * add new user
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {

        $form = $this->getForm('user');

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('user');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $data['request'] = $this->getRequest();

                if ($this->getService()->addUser($data)) {
                    $this->flashMessenger()->addMessage('new user added successfully');
                } else {
                    $this->flashMessenger()->addMessage('unable to send verify email');
                }


                return $this->redirect()->toRoute('user');
            }
        }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );
    }

    /**
     * reset the user's password (send mail)
     *
     * @return \Zend\Http\Response
     */
    public function resetPasswordAction()
    {

       //@todo: nachfrage double_opt passwort zurücksetzen?YN

       //get param
       $uid  = $this->params()->fromRoute('id', null);

       $data['uid'] = $uid;
       $data['request'] = $this->getRequest();
       $this->getService()->resetPassword($data);

       return $this->redirect()->toRoute('user');
    }

    /**
     * edit a user
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {

        //get param
       $uid  = $this->params()->fromRoute('id', null);

       /* @var $form \User\Form\UserForm */
       $form = $this->getForm(UserFormService::USER_FORM);
       $user=$this->getUserMapper()->getUserById($uid);
       $form->bindEntity($user);

       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('user');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData();
                $this->getService()->editUser($data);

                return $this->redirect()->toRoute('user');
            }
       }

        return new ViewModel(
            array(
              'form'    => $form
            )
        );

    }

    /**
     * deactivate a user
     *
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
       //get param
       $uid  = $this->params()->fromRoute('id', null);
       $this->getService()->deleteUser($uid);

       return $this->redirect()->toRoute('user');
    }

    /**
     * reactivate a user
     *
     * @return \Zend\Http\Response
     */
    public function undeleteAction()
    {
       //get param
       $uid  = $this->params()->fromRoute('id', null);
       $this->getService()->undeleteUser($uid);

       return $this->redirect()->toRoute('user');
    }

    /**
     * @return \User\Mapper\UserMapper
     */
    private function getUserMapper()
    {
        /* @var $repo \User\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(\User\Services\RepositoryService::USER_MAPPER);
    }


}
