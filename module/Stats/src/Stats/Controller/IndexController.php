<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Stats\Calculation\MatchStatsFactory;
use Stats\Entity\Championship;
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
                    $stats->addPosition($player->getPosition());
                }

                //todo: distinguish between league and tournament
                if ($tournament->getNumber() == 1) {

                    switch($player->getPosition()) {
                        case 1: $stats->getChampion()->addGold();
                            break;
                        case 2: $stats->getChampion()->addSilver();
                            break;
                        case 3: $stats->getChampion()->addBronze();
                            break;
                    }
                } elseif ($tournament->getNumber() > 1) {
                    switch($player->getPosition()) {
                        case 1: $stats->getMedal()->addGold();
                            break;
                        case 2: $stats->getMedal()->addSilver();
                            break;
                        case 3: $stats->getMedal()->addBronze();
                            break;
                    }
                }
            }

        }

        $userMatches = $mapper->getConsecutiveMatchesByUser($userId);

        /* @var $match \Season\Entity\Match */
        $conWin=array();
        $win = 0;
        foreach ($userMatches as $match) {

            if ($match->getResult()->hasWinner() && $match->getResult()->getWinner()->getId() == $userId) {
                $win++;
            } else {
                $conWin[] = $win;
                $win = 0;
            }
        }

        $max = max($conWin);
        $stats->setNoConsecutiveWins($max);


        return new ViewModel(
            array(
                'player' => $stats,
            )
        );
    }


}
