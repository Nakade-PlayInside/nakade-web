<?php
namespace Stats\Controller;

use Nakade\Pagination\ItemPagination;
use DOMPDFModule\View\Model\PdfModel;
use Stats\Pagination\TournamentPagination;
use Zend\View\Model\ViewModel;
/**
 *
 * @package Stats\Controller
 */
class IndexController extends DefaultController
{

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        //todo: caching of factory results
        return new ViewModel(
            array(
                'player' => $this->getPlayerStats(),
            )
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function tournamentAction()
    {
        //todo: caching of factory results
        $page = (int) $this->params()->fromRoute('id', 1);
        $stats = $this->getPlayerStats();

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

        //todo: caching of factory results
        $stats = $this->getPlayerStats();
        $tournaments = $stats->getTournaments();

        $pagination =  new TournamentPagination($tournaments);
        $pagination->setCurrentPageByItemId($lid);

        /* @var $league \Season\Entity\League */
        $league =  $this->getLeagueMapper()->getLeagueById($lid);
        $matches = $this->getLeagueMapper()->getMatchesByLeague($lid);

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
        //todo: caching of factory results

        return new ViewModel(
            array(
                'league' => $this->getLeagueMapper()->getLeagueById($lid),
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
        //todo: caching of factory results

        $stats = $this->getPlayerStats();
        $matchStats = $this->getMatchStats();

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
        //todo: caching of factory results
        $stats = $this->getPlayerStats();

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
        //todo: caching of factory results
        $stats = $this->getPlayerStats();
        $matchStats = $this->getMatchStats();

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
        //todo: caching of factory results
        $stats = $this->getPlayerStats();
        $matchStats = $this->getMatchStats();

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

        $stats = $this->getPlayerStats();
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
     * @return PdfModel|ViewModel
     */
    public function certificateAction()
    {
        $userId = $this->identity()->getId();
        $lid  = $this->params()->fromRoute('id', null);

        $certificate = $this->getCertificateService()->getCertificate($userId, $lid);

        $pdf = new PdfModel();
        $pdf->setOption('filename', 'certificate'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'portrait'); // Defaults to "portrait"

        // To set view variables
        $pdf->setVariables(array(
            'certificate' => $certificate,
        ));

        return $pdf;
    }


}
