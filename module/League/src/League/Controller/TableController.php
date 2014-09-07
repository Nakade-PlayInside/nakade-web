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
        $league = $this->getActualLeague();
        $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());
        $matchDay = $this->getResultMapper()->getActualMatchDayByLeague($league->getId());

        if (empty($matchDay)) {
            $matchDay=1;
        }

        return new ViewModel(
            array(
                'tournament'   => $league,
                'matchDay' => $matchDay,
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
        $sortBy  = $this->params()->fromRoute('sort', SortingInterface::BY_POINTS);
        $leagueNo  = $this->params()->fromRoute('league', null);

        $sortBy = str_replace('sort=', '', $sortBy);
        if (!is_null($leagueNo)) {
            $leagueNo = str_replace('league=', '', $leagueNo);
        }



        $league = $this->getActualLeague($leagueNo);
        $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());

        return new ViewModel(
            array(
                'tournament'  => $league,
                'table'   => $this->getPlayersTable($matches, $sortBy),
                'paginator' => $this->getLeaguePaginator()->getPagination($league->getNumber()),
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
