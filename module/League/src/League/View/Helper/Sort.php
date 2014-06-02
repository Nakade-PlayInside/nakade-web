<?php
namespace League\View\Helper;

use League\Standings\Sorting\SortingInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Active
 *
 * @package League\View\Helper
 */
class Sort extends AbstractHelper implements SortingInterface
{

    /**
     * @param string $sort
     *
     * @return string
     */
    public function __invoke($sort=self::BY_NAME)
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
        $request = $sm->get('request');
        $router = $sm->get('router');

        /* @var $routeMatch \Zend\Mvc\Router\RouteMatch */
        $routeMatch = $router->match($request);
        $route = $routeMatch->getMatchedRouteName();
        $action = $routeMatch->getParam('action');

        return $this->getView()->url($route, array('action' => $action, 'sort' => $sort));

    }

}
