<?php
namespace Permission\Controller\Plugin;

use Permission\Entity\RoleInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use \Zend\Permissions\Acl\Acl;

/**
 * Class AbstractPermission
 *
 * @package Permission\Controller\Plugin
 */
abstract class AbstractPermission extends AbstractPlugin implements AuthorizeInterface, RoleInterface
{

    protected $event;
    protected $serviceManager;
    protected $resourceController;
    protected $resourceAction;
    protected $acl;
    protected $role;


    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ServiceLocatorInterface $serviceManager
     *
     * @return $this
     */
    public function setServiceManager(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @param Acl $acl
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @return Acl
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * get event
     *
     * @return MvcEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param MvcEvent $event
     *
     * @return $this
     */
    public function setEvent(MvcEvent $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @param string $resourceAction
     *
     * @return $this
     */
    public function setResourceAction($resourceAction)
    {
        $this->resourceAction = $resourceAction;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceAction()
    {
        return $this->resourceAction;
    }

    /**
     * @param string $resourceController
     *
     * @return $this
     */
    public function setResourceController($resourceController)
    {
        $this->resourceController = $resourceController;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceController()
    {
        return $this->resourceController;
    }

    /**
     * @return bool
     */
    public function hasResource()
    {
        $controller = $this->getResourceController();
        $action = $this->getResourceAction();

        return $this->getAcl()->hasResource($controller) || $this->getAcl()->hasResource($action);
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        $acl = $this->getAcl();
        $controller = $this->getResourceController();
        $action = $this->getResourceAction();

        // first check controller if no action is registered
        if ($acl->hasResource($controller) && !$acl->hasResource($action)) {
            return $acl->isAllowed($this->getRole(), $controller);
        } elseif ($acl->hasResource($action)) {
            return $acl->isAllowed($this->getRole(), $action);
        }

        return false;
    }


    /**
     * @param AuthenticationService $auth
     *
     * @return string
     */
    protected function getRoleByIdentity(AuthenticationService $auth)
    {
        $role = self::DEFAULT_ROLE;

        if ($auth->hasIdentity()) {
            /* @var $identity \User\Entity\User */
            $identity = $auth->getIdentity();
            $role = $identity->getRole();
        }

        return $role;
    }

    /**
     * @param string $typ
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    protected function getService($typ)
    {
        $service = null;
        switch($typ) {

            case 'Permission\Services\AclService':
                $service = $this->getServiceManager()->get('Permission\Services\AclService');
                break;
            case 'Zend\Authentication\AuthenticationService':
                $service = $this->getServiceManager()->get('Zend\Authentication\AuthenticationService');
                break;
            default:
                throw new \RuntimeException(
                    sprintf('An unknown service type was provided.')
                );
        }

        if (is_null($service)) {
            throw new \RuntimeException(
                sprintf('Service was not found:' . $typ)
            );
        }

        return $service;
    }

    /**
     * set the redirection and stops the propagation
     *
     * @param string $route
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function redirectToRoute($route)
    {

        $event = $this->getEvent();
        $router = $event->getRouter();
        $url    = $router->assemble(array(), array('name' => $route));

        /* @var $response \Zend\Http\Response */
        $response = $event->getResponse();
        $response->setStatusCode(302);

        //redirect to login route...
        /* change with header('location: '.$url); if code below not working */
        $response->getHeaders()->addHeaderLine('Location', $url);
        $event->stopPropagation();

        return $response;

    }
}

