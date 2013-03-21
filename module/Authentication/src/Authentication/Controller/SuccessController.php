<?php
//module/SanAuth/src/SanAuth/Controller/SuccessController.php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Success controller for successful authentication of registered users.
 */
class SuccessController extends AbstractActionController
{
    public function indexAction()
    {
       /** if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }**/
        if($this->identity()){
            $mes= "loggedIn";
        }
        else $mes="loggedOut";
        
        return new ViewModel( array('loggedIn' => $mes ));
    }
}