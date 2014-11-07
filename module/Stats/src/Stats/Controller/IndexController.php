<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
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

        $wins = $draws = $suspended = 0;
        /* @var $match \Season\Entity\Match */
        foreach($matches as $match) {

            if($match->getResult()->getResultType() == 3) {
                $draws++;
                continue;
            }

            if($match->getResult()->getResultType() == 5) {
                $suspended++;
                continue;
            }


            if($match->getResult()->getWinner() == $userId) {
                $wins++;
            }
        }
        var_dump($wins);
        var_dump($draws);
        var_dump($suspended);

        $stats = new MatchStats();
        $stats->setGamesPlayed(count($matches));

        return new ViewModel(
            array('stats' => $stats)
        );
    }


}
