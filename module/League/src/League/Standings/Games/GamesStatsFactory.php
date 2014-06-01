<?php
namespace League\Standings\Games;

use League\Standings\StatsFactory;
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
               $stats = PlayedGames::getInstance();
               break;

           case self::GAMES_LOST:
               $stats = LostGames::getInstance();
               break;

           case self::GAMES_WON:
               $stats = WonGames::getInstance();
               break;

           case self::GAMES_DRAW:
               $stats = DrawGames::getInstance();
               break;

           case self::GAMES_SUSPENDED:
               $stats = SuspendedGames::getInstance();
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

