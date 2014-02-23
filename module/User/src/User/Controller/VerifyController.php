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

use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Verify the account with new credentials.
 * Use the link of the activation mail send to the user.
 */
class VerifyController extends AbstractController
{

    /**
     * Verification action. A direct link to this action is provided
     * in the user's verification mail. 
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

       $email          = $this->params()->fromQuery('email', null);
       $verifyString   = $this->params()->fromQuery('verify', null);

       //no params -> error
       if (!isset($email) || !isset($verifyString)) {
           return $this->redirect()->toRoute('verify', array('action' => 'error'));
       }

       $isActivated = $this->getService()->activateUser($email, $verifyString);

       if (! $isActivated) {
           return $this->redirect()->toRoute('verify', array('action' => 'failure'));
       }

       //OK!
       return new ViewModel(
           array()
       );

    }

    /**
     * Activation failed.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function failureAction()
    {
        return new ViewModel();
    }

    /**
     * Data missing.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function errorAction()
    {
        return new ViewModel();
    }
}
