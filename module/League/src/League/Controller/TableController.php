<?php
namespace League\Controller;

use League\Pagination\LeaguePagination;
use League\Standings\MatchStats;
use League\Standings\Sorting\SortingInterface;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use League\Standings\Sorting\PlayerPosition;
use League\Standings\Sorting\PlayerSorting as SORT;

/**
 * Class TableController
 *
 * @package League\Controller
 */
class TableController extends DefaultController
{

    /**
    * top league widget
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {
        $league = $this->getActualLeague();
        $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());

        return new ViewModel(
            array(
              'tournament'   => $league,
              'table'    => $this->getPlayersTable($matches),
            )
        );
    }


    /**
    * Shows the user's league table. If user is not in a league, the
    * Top league is shown instead. The Table is sortable.
    *
    * @return array|ViewModel
    */
    public function detailedAction()
    {
       $sortBy  = $this->params()->fromRoute('sort');
       $leagueNo  = $this->params()->fromRoute('league');

       $sortBy = str_replace('sort=', '', $sortBy);
       $leagueNo = str_replace('league=', '', $leagueNo);


       $league = $this->getActualLeague($leagueNo);
       $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());

       return new ViewModel(
           array(
              'tournament'  => $league,
              'table'   => $this->getPlayersTable($matches, $sortBy),
              'paginator' => $this->getPaginator()->getPagination($league->getNumber()),
           )
       );
    }

    /**
     * @return \Season\Entity\Season|null
     */
    private function getActualSeason()
    {
        $association = 1;
        return $this->getSeasonMapper()->getActiveSeasonByAssociation($association);
    }


    /**
     * @param null|int $leagueNo
     *
     * @return null|\Season\Entity\League
     */
    private function getActualLeague($leagueNo=null)
    {
        $season = $this->getActualSeason();
        $league = null;

        if(is_null($season)) {
            return  $league;
        }

        if(!is_null($leagueNo)) {
            return $this->getLeagueMapper()->getLeagueByNumber($season, $leagueNo);
        }

        $user = $this->identity();
        if(!empty($user)) {
            $league = $this->getScheduleMapper()->getLeagueByUser($season->getId(), $user->getId());
        }

        if (empty($league)) {
            $league = $this->getLeagueMapper()->getTopLeagueBySeason($season->getId());
        }

        return  $league;
    }


    /**
     * @return LeaguePagination
     */
    private function getPaginator()
    {
        $leaguesInSeason = array();
        $season = $this->getActualSeason();

        if(!is_null($season)) {
            $leaguesInSeason = $this->getLeagueMapper()->getLeaguesBySeason($season->getId());
        }

        return  new LeaguePagination($leaguesInSeason);
    }

    /**
     * @param array  $matches
     * @param string $sort
     *
     * @return array
     */
    private function getPlayersTable(array $matches, $sort=SortingInterface::BY_POINTS)
    {
        $info = new MatchStats($matches);
        $players = $info->getMatchStats();
        $sorting = SORT::getInstance();
        $sorting->sorting($players, $sort);
        $pos = new PlayerPosition($players, $sort);
        return $pos->getPosition();
    }


}
