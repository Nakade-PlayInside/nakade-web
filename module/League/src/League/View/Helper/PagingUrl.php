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
     * @param int    $page
     * @param string $param
     *
     * @return mixed
     */
    public function __invoke($page, $param='id')
    {
        $route = $this->getRouteMatch()->getMatchedRouteName();
        $action = $this->getRouteMatch()->getParam('action');

        return $this->getView()->url($route, array('action' => $action, $param => $page));
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
