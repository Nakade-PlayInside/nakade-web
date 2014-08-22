<?php
namespace Permission\Controller\Plugin;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Authorize
 *
 * @package Permission\Controller\Plugin
 *
 */
class Authorize extends AbstractPermission implements AuthorizeInterface
{

    /**
     * @param MvcEvent $event
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function doAuthorization(MvcEvent $event)
    {
        $this->event = $event;
        $this->serviceManager = $event->getApplication()->getServiceManager();

        $aclService = $this->getService('Permission\Services\AclService');
        $authService = $this->getService('Zend\Authentication\AuthenticationService');
        $this->repository = $this->getServiceManager()->get('Support\Services\RepositoryService');
        $routeMatch = $this->getEvent()->getRouteMatch();

        $this->resourceController = $routeMatch->getParam('controller');
        $this->resourceAction = $this->getResourceController() .'\\'. $routeMatch->getParam('action');
        $this->role = $this->getRoleByIdentity($authService);
        $this->authority = $this->getAuthorityByIdentity($authService);
        $this->acl = $aclService->getAcl();

        //permission check
        if ($this->hasResource()) {

            //not logged in
            if ($this->getRole() === self::DEFAULT_ROLE) {
                return $this->redirectToRoute('login');
            }

            //lookup allowance
            if (!$this->isAllowed()) {
                return $this->redirectToRoute('forbidden');
            }
        }

        return $event->getResponse();
    }

}

