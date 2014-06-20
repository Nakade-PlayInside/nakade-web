<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
/**
 * Class PagingUrl
 *
 * @package League\View\Helper
 */
class PagingUrl extends AbstractHelper
{
    /**
     * @param int $page
     *
     * @return string
     */
    public function __invoke($page)
    {
        $route = $this->getRouteMatch()->getMatchedRouteName();
        $action = $this->getRouteMatch()->getParam('action');

        return $this->getView()->url($route, array('action' => $action, 'id' => $page));
    }


    /**
     * @return \Zend\Mvc\Router\RouteMatch
     */
    private function getRouteMatch()
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
        $request = $sm->get('request');
        $router = $sm->get('router');

        return $router->match($request);
    }

}
