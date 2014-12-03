<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Nakade\Result\ResultInterface;
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

        //var_dump($tournaments);

        $stats = new PlayerStats();
        $stats->setTournaments($tournaments);

        $userMatches = $mapper->getConsecutiveMatchesByUser($userId);


        /* @var $match \Season\Entity\Match */
        $consecutiveWins = array();
        $wins = array();
        $loss = array();
        $draws = array();
        foreach ($userMatches as $match) {

            if ($match->getResult()->hasWinner() && $match->getResult()->getWinner()->getId() == $userId) {
                $consecutiveWins[] = $match;
                $wins[]=$match;
            } else {
                if (count($consecutiveWins) >= $stats->getNoConsecutiveWins()) {
                    $stats->setConsecutiveWins($consecutiveWins);
                }
                if ($match->getResult()->hasWinner() && $match->getResult()->getWinner()->getId() != $userId) {
                    $loss[] = $match;
                }
                if ($match->getResult()->getResultType() == ResultInterface::DRAW) {
                    $draws[] = $match;
                }
                $consecutiveWins = array();
            }
        }
        $stats->setWins($wins);
        $stats->setLoss($loss);
        $stats->setDraws($draws);


        foreach ($tournaments as $tournament)
        {
            $matches = $mapper->getMatchesByTournament($tournament->getId());

            $isOngoing = false;
            foreach ($matches as $match) {
                if (!$match->hasResult()) {
                    $isOngoing = true;
                    break;
                }
            }

            if ($isOngoing) continue;

            $table = $this->getService()->getTable($matches);

            /* @var  $player \League\Entity\Player */
            foreach ($table as $player) {

                if ($player->getUser()->getId() == $userId) {

                    //having the tournament provides data for the winner certificate
                    //todo: distinguish between league and tournament
                    if ($tournament->getNumber() == 1) {

                        switch($player->getPosition()) {
                            case 1: $stats->getChampion()->addGold($tournament);
                                break;
                            case 2: $stats->getChampion()->addSilver($tournament);
                                break;
                            case 3: $stats->getChampion()->addBronze($tournament);
                                break;
                        }
                    }
                }


            }

        }


        return new ViewModel(
            array(
                'player' => $stats,
            )
        );
    }


}
