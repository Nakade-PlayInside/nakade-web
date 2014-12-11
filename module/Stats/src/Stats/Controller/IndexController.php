<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Nakade\Services\PlayersTableService;
use Nakade\Pagination\ItemPagination;
use Stats\Calculation\ContingencyTableFactory;
use Stats\Calculation\MatchStatsFactory;
use Stats\Pagination\TournamentPagination;
use Stats\Services\CrossTableService;
use Stats\Services\RepositoryService;
use Zend\View\Model\ViewModel;
/**
 *
 * @package Stats\Controller
 */
class IndexController extends AbstractController
{
    private $tableService;
    private $crossTableService;

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
    public function tournamentAction()
    {

        $page = (int) $this->params()->fromRoute('id', 1);

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        $tournaments = $stats->getTournaments();
        $pagination = new ItemPagination($tournaments);

        return new ViewModel(
            array(
                'tournaments' => $pagination->getOffsetArray($stats->getTournaments(), $page),
                'paginator' => $pagination->getPagination($page),
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

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);
        $tournaments = $stats->getTournaments();

        $pagination =  new TournamentPagination($tournaments);
        $pagination->setCurrentPageByItemId($lid);

        /* @var $league \Season\Entity\League */
        $league =  $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER)->getLeagueById($lid);
        $matches = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER)->getMatchesByLeague($lid);

        $isOngoing = $this->getService()->getAchievement()->isOngoing($matches);
        $league->setIsOngoing($isOngoing);

        return new ViewModel(
            array(
                'tournament'  => $league,
                'table'   => $this->getTableService()->getTable($matches),
                'pagination' => $pagination,
            )
        );

    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function crossAction()
    {
        $lid  = $this->params()->fromRoute('id', 1);

        /* @var $league \Season\Entity\League */
        $league =  $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER)->getLeagueById($lid);

        return new ViewModel(
            array(
                'league' => $league,
                'table'   => $this->getCrossTableService()->getTable($lid),
            )
        );
    }


    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function matchAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $matchStats = $factory->getMatchStats();

        $myGames = $stats->getMatches();
        $pagination = new ItemPagination($myGames);

        return new ViewModel(
            array(
                'matches' => $pagination->getOffsetArray($myGames, $page),
                'total' => count($myGames),
                'stats' => $matchStats,
                'paginator' => $pagination->getPagination($page),

            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function consecutiveAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        $myGames = $stats->getConsecutiveWins();
        $pagination = new ItemPagination($myGames);

        return new ViewModel(
            array(
                'matches' => $pagination->getOffsetArray($myGames, $page),
                'total' => count($myGames),
                'paginator' => $pagination->getPagination($page),
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function winAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $matchStats = $factory->getMatchStats();

        $myGames = $stats->getWins();
        $pagination = new ItemPagination($myGames);

        return new ViewModel(
            array(
                'matches' => $pagination->getOffsetArray($myGames, $page),
                'total' => count($myGames),
                'stats' => $matchStats,
                'paginator' => $pagination->getPagination($page),

            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function defeatAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);
        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $matchStats = $factory->getMatchStats();

        $myGames = $stats->getLoss();
        $pagination = new ItemPagination($myGames);

        return new ViewModel(
            array(
                'matches' => $pagination->getOffsetArray($myGames, $page),
                'total' => count($myGames),
                'stats' => $matchStats,
                'paginator' => $pagination->getPagination($page),
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function drawAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $userId = $this->identity()->getId();
        /* @var $stats \Stats\Entity\PlayerStats */
        $stats = $this->getService()->getPlayerStats($userId);
        $myGames = $stats->getDraws();
        $pagination = new ItemPagination($myGames);

        return new ViewModel(
            array(
                'matches' => $pagination->getOffsetArray($myGames, $page),
                'total' => count($myGames),
                'paginator' => $pagination->getPagination($page),
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

    /**
     * @param CrossTableService $crossTableService
     */
    public function setCrossTableService(CrossTableService $crossTableService)
    {
        $this->crossTableService = $crossTableService;
    }

    /**
     * @return CrossTableService
     */
    public function getCrossTableService()
    {
        return $this->crossTableService;
    }

}
