<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Stats\Calculation\MatchStatsFactory;
use Stats\Entity\MatchStats;
use Stats\Services\RepositoryService;
use Zend\View\Model\ViewModel;

/**
 *
 * @package Stats\Controller
 */
class IndexController extends AbstractController
{
    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

        $userId = $this->identity()->getId();

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        var_dump($factory->getMatchStats());

        return new ViewModel(
            array('stats' => array())
        );
    }


}
