<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Stats\Calculation\MatchStatsFactory;
use Stats\Entity\PlayerStats;
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
        $stats = $factory->getMatchStats();

        return new ViewModel(
            array('stats' => $stats)
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function achievementAction()
    {

        $userId = $this->identity()->getId();

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        /* @var $tournament \Season\Entity\League */
        $tournaments = $mapper->getTournamentsByUser($userId);

        // service
        // noTournaments
        // matches
        // win
        // draws
        // losses
        // noOfConsecutiveWins
        // positions
        // achievements (placement)

      //  var_dump(count($tournaments));

        $stats = new PlayerStats();
        $stats->setNoTournaments(count($tournaments));

        foreach ($tournaments as $tournament)
        {
            $matches = $mapper->getMatchesByTournament($tournament->getId());
            $table = $this->getService()->getTable($matches);


            /* @var  $player \League\Entity\Player */
            foreach ($table as $player) {

                if ($player->getUser()->getId() == $userId) {

                    $stats->addNoGames($player->getGamesPlayed());
                    $stats->addNoWin($player->getGamesWin());
                    $stats->addNoLoss($player->getGamesLost());
                    $stats->addNoDraw($player->getGamesDraw());

                }
            }

        }


    //    var_dump($pos);
        //var_dump($tournaments);die;
       // $factory = new MatchStatsFactory($matches, $userId);
       // $stats = $factory->getMatchStats();

        return new ViewModel(
            array(
                'player' => $stats,
            )
        );
    }


}
