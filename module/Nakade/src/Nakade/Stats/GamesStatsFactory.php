<?php
namespace Nakade\Stats;

use Nakade\Stats\StatsFactory;
use Nakade\Stats\Games;
use RuntimeException;

/**
 * Class GamesStatsFactory
 *
 * @package League\Standings\Games
 */
class GamesStatsFactory extends StatsFactory
{
    const GAMES_PLAYED = 'played';
    const GAMES_LOST = 'lost';
    const GAMES_WON = 'won';
    const GAMES_DRAW = 'draw';
    const GAMES_SUSPENDED = 'suspended';

    /**
     * Constructor providing an array of match entities
     * @param array $matches
     */
    public function __construct(array $matches)
    {
        $this->matches=$matches;
    }

    /**
     * @param string $typ
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function getPoints($typ)
    {

        switch (strtolower($typ)) {

           case self::GAMES_PLAYED:
               $stats = Games\PlayedGames::getInstance();
               break;

           case self::GAMES_LOST:
               $stats = Games\LostGames::getInstance();
               break;

           case self::GAMES_WON:
               $stats = Games\WonGames::getInstance();
               break;

           case self::GAMES_DRAW:
               $stats = Games\DrawGames::getInstance();
               break;

           case self::GAMES_SUSPENDED:
               $stats = Games\SuspendedGames::getInstance();
               break;

           default      :
               throw new RuntimeException(
                   sprintf('An unknown stats was provided.')
               );
        }

         if ($this->getPlayerId() == null) {
            throw new RuntimeException(
                sprintf('PlayerId has to be set. Found:null')
            );
         }

        $stats->setMatches($this->getMatches());
        return $stats->getNumberOfGames($this->getPlayerId());
    }
}

