<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Nakade\Services\PlayersTableService;
use Nakade\Standings\Sorting\SortingInterface;
use Stats\Calculation\MatchStatsFactory;
use Stats\Services\RepositoryService;
use Zend\View\Model\ViewModel;
/**
 *
 * @package Stats\Controller
 */
class IndexController extends AbstractController
{
    private $tableService;

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

        $userId = $this->identity()->getId();
        $stats = $this->getService()->getPlayerStats($userId);

        return new ViewModel(
            array(
                'player' => $stats,
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function achievementAction()
    {

        $userId = $this->identity()->getId();
        $stats = $this->getService()->getPlayerStats($userId);

        return new ViewModel(
            array(
                'player' => $stats,
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function tournamentAction()
    {

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        return new ViewModel(
            array(
                'tournaments' => $stats->getTournaments(),
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function tableAction()
    {
        $lid  = $this->params()->fromRoute('id', null);

        $league =  $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER)->getLeagueById($lid);
        $matches = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER)->getMatchesByLeague($lid);

        $isOngoing = $this->getService()->getAchievement()->isOngoing($matches);
        $league->setIsOngoing($isOngoing);

        return new ViewModel(
            array(
                'tournament'  => $league,
                'table'   => $this->getTableService()->getTable($matches),
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function matchAction()
    {
        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $matchStats = $factory->getMatchStats();

        return new ViewModel(
            array(
                'matches' => $stats->getMatches(),
                'stats' => $matchStats
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function consecutiveAction()
    {
        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        return new ViewModel(
            array(
                'matches' => $stats->getConsecutiveWins(),
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function winAction()
    {
        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $matchStats = $factory->getMatchStats();

        return new ViewModel(
            array(
                'matches' => $stats->getWins(),
                'stats' => $matchStats
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function defeatAction()
    {
        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);
        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $matchStats = $factory->getMatchStats();

        return new ViewModel(
            array(
                'matches' => $stats->getLoss(),
                'stats' => $matchStats
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function drawAction()
    {
        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        return new ViewModel(
            array(
                'matches' => $stats->getDraws(),
            )
        );
    }


    /**
     * @param PlayersTableService $tableService
     */
    public function setTableService(PlayersTableService $tableService)
    {
        $this->tableService = $tableService;
    }

    /**
     * @return PlayersTableService
     */
    public function getTableService()
    {
        return $this->tableService;
    }




}
