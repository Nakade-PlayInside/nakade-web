<?php
namespace League\View\Helper;

use Nakade\Standings\Sorting\SortingInterface;
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
     * @param int $leagueNo
     *
     * @return mixed
     */
    public function __invoke($sort=self::BY_NAME, $leagueNo=1)
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
        $request = $sm->get('request');
        $router = $sm->get('router');

        /* @var $routeMatch \Zend\Mvc\Router\RouteMatch */
        $routeMatch = $router->match($request);
        $route = $routeMatch->getMatchedRouteName();
        $action = $routeMatch->getParam('action');

        return $this->getView()->url($route, array(
            'action' => $action,
            'sort' => 'sort=' . $sort,
            'league' => 'league=' . $leagueNo));

    }

}
