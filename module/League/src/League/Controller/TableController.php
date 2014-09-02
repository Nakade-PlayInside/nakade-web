<?php
namespace League\Controller;

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
        $sortBy  = $this->params()->fromRoute('sort', SortingInterface::BY_POINTS);

        $season = $this->getSeasonMapper()->getActiveSeasonByAssociation(1);
        $league = $this->getLeagueMapper()->getTopLeagueBySeason($season->getId());
        $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());

        return new ViewModel(
            array(
              'tournament'   => $league,
              'table'    => $this->getPlayersTable($matches, $sortBy),
            )
        );
    }


    /**
    * Shows the user's league table. If user is not in a league, the
    * Top league is shown instead. The Table is sortable.
    *
    * @return array|ViewModel
    */
    public function tableAction()
    {
       //sorting the table
       $sortBy  = $this->params()->fromRoute('sort', SortingInterface::BY_POINTS);

       $userId = $this->identity()->getId();
       $season = $this->getSeasonMapper()->getActiveSeasonByAssociation(1);
       $league = $this->getScheduleMapper()->getLeagueByUser($season->getId(), $userId);

       if (is_null($league)) {
           $league = $this->getLeagueMapper()->getTopLeagueBySeason($season->getId());
       }
       $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());

       return new ViewModel(
           array(
              'tournament'  => $league,
              'table'   => $this->getPlayersTable($matches, $sortBy),
           )
       );
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
