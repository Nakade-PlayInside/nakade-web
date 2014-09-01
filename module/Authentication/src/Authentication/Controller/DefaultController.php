<?php
namespace Authentication\Controller;

use Authentication\Entity\Login;
use Authentication\Session\FailureContainer;
use Zend\Authentication\AuthenticationService;
use Nakade\Abstracts\AbstractController;
use Authentication\Services\FormFactory;

/**
 * Class DefaultController
 *
 * @package Authentication\Controller
 */
class DefaultController extends AbstractController
{
    protected $container;

    /**
     * @return \Authentication\Form\AuthForm
     */
    protected function getLoginForm()
    {
        return $this->getForm(FormFactory::AUTH);
    }

    /**
    * @return \Authentication\Session\FailureContainer
    */
    public function getFailureContainer()
    {
        return $this->container;
    }

    /**
     * @param \Authentication\Session\FailureContainer $container
     */
    public function setFailureContainer(FailureContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @return AuthenticationService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param AuthenticationService $service
     *
     * @return $this
     */
    public function setService(AuthenticationService $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return \Authentication\Adapter\AuthAdapter
     */
    protected function getAdapter()
    {
        return $this->getService()->getAdapter();
    }

    /**
     * @return \Authentication\Adapter\AuthStorage
     */

    protected function getStorage()
    {
        return $this->getService()->getStorage();
    }

    /**
     * @param Login $login
     *
     * @return \Zend\Authentication\Result
     */
    protected function authenticate(Login $login)
    {
        $this->getAdapter()->setIdentityValue($login->getIdentity())->setCredentialValue($login->getPassword());
        return $this->getService()->authenticate();
    }


}
