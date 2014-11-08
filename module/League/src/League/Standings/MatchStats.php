<?php
namespace League\Standings;

use League\Entity\Player;
use League\Standings\Tiebreaker\TiebreakerFactory;
use Nakade\Stats\GamesStatsFactory;

/**
 * Determining the games stats for having a sorted table of
 * a league.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchStats extends MatchInfo
{
    private $gamesStatsFactory;
    private $tieBreakerFactory;

    /**
     * @param array $matches
     */
    public function __construct(array $matches)
    {

        if (!empty($matches)) {

            $this->setMatches($matches);
            /* @var $match \Season\Entity\Match */
            $match = $matches[0];

            $data = array();
            $data['tieBreaker1'] = $match->getLeague()->getSeason()->getTieBreaker1()->getName();
            $data['tieBreaker2'] = $match->getLeague()->getSeason()->getTieBreaker2()->getName();
            $data['tieBreaker3'] = $match->getLeague()->getSeason()->getTieBreaker3()->getName();
            $data['winPoints'] = $match->getLeague()->getSeason()->getWinPoints();

            $users = array();
            foreach ($matches as $match) {
                $black = $match->getBlack();
                if (!in_array($black, $users)) {
                    $users[] = $black;
                }

                $white = $match->getWhite();
                if (!in_array($white, $users)) {
                    $users[] = $white;
                }
            }

            $players = array();
            foreach ($users as $user) {
                $players[] = new Player($user);
            }
            $data['players'] = $players;
            $this->exchangeArray($data);
        }
    }
    /**
     * setter TiebreakerFactory
     *
     * @param TiebreakerFactory $factory
     *
     * @return $this
     */
    public function setTiebreakerFactory($factory)
    {
        $this->tieBreakerFactory = $factory;
        return $this;
    }

    /**
     * getter TiebreakerFactory
     * @return TiebreakerFactory
     */
    public function getTiebreakerFactory()
    {
        if (is_null($this->tieBreakerFactory)) {

            $this->tieBreakerFactory =
                new TiebreakerFactory($this->matches);
        }
        return $this->tieBreakerFactory;
    }

    /**
     * setter GamesStats Factory
     *
     * @param GamesStatsFactory $factory
     *
     * @return $this
     */
    public function setGamesStatsFactory($factory)
    {
        $this->gamesStatsFactory = $factory;
        return $this;
    }

    /**
     * getter GamesStats Factory
     * @return GamesStatsFactory
     */
    public function getGamesStatsFactory()
    {
        if (is_null($this->gamesStatsFactory)) {

            $this->gamesStatsFactory =
                new GamesStatsFactory($this->matches);
        }

        return $this->gamesStatsFactory;
    }


    /**
     * @return array
     */
    public function getMatchStats()
    {

        $players = $this->getPlayers();

        /* @var $player \League\Entity\Player */
        //putting the calculated stats into the players
        for ($i = 0; $i < count($players); ++$i) {
           $player =  $players[$i];
           $data = $this->getPlayersStats($player->getUser()->getId());
           $player->exchangeArray($data);
        }

        return $players;
    }

    private function getPlayersStats($playerId)
    {
        $this->getGamesStatsFactory()->setPlayerId($playerId);
        $this->getTiebreakerFactory()->setPlayerId($playerId);

        $results = array(
           'gamesPlayed'    => $this->getGamesStatsFactory()
                                    ->getPoints(GamesStatsFactory::GAMES_PLAYED),

           'gamesSuspended' => $this->getGamesStatsFactory()
                                    ->getPoints(GamesStatsFactory::GAMES_SUSPENDED),

           'gamesWin'       => $this->getGamesStatsFactory()
                                    ->getPoints(GamesStatsFactory::GAMES_WON),

           'gamesDraw'      => $this->getGamesStatsFactory()
                                    ->getPoints(GamesStatsFactory::GAMES_DRAW),

           'gamesLost'      => $this->getGamesStatsFactory()
                                    ->getPoints(GamesStatsFactory::GAMES_LOST),

           'gamesPoints'    => $this->getPoints(),

           'firstTiebreak'  => $this->getTiebreakerFactory()
                                    ->getPoints($this->getTiebreaker1()),

           'secondTiebreak' => $this->getTiebreakerFactory()
                                    ->getPoints($this->getTiebreaker2()),

           'thirdTiebreak' => $this->getTiebreakerFactory()
                                    ->getPoints($this->getTiebreaker3()),

        );

        return $results;
    }

    private function getPoints()
    {
        return  (int) $this->getGamesStatsFactory()->getPoints(GamesStatsFactory::GAMES_WON)  *
                (int) $this->getWinPoints() +
                (int) $this->getGamesStatsFactory()->getPoints(GamesStatsFactory::GAMES_DRAW) *
                (int) $this->getDrawPoints();
    }

}

