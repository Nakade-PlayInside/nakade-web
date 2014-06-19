<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * Class Open
 *
 * @package League\View\Helper
 */
class OpenResult extends AbstractHelper implements HighlightInterface
{
    /**
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
        $today = new \DateTime();
        $result = $this->getView()->result($match);
        if (!$match->hasResult() && $match->getDate() < $today) {

            $result = sprintf('<a href="%s" title="%s">%s</a>',
                $this->getUrl($match),
                $this->getView()->translate("Enter Result"),
                $this->getView()->translate("open")
            );
        }

        return $result;

    }

    /**
     * @param Match $match
     *
     * @return mixed
     */
    private function getUrl(Match $match)
    {
        $route = $this->getRouteMatch()->getMatchedRouteName();
        return $this->getView()->url($route, array('action' => 'add', 'id' => $match->getId()));
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
