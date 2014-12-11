<?php
namespace League\Controller;

use Nakade\Standings\Sorting\SortingInterface;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

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
                'table'    => $this->getService()->getTable($matches),
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
        $league = $this->getActualLeague($leagueNo);
        $matches = $this->getLeagueMapper()->getMatchesByLeague($league->getId());

        return new ViewModel(
            array(
                'tournament'  => $league,
                'table'   => $this->getService()->getTable($matches, $sortBy),
                'paginator' => $this->getLeaguePaginator()->getPagination($league->getNumber()),
            )
        );
    }

}
