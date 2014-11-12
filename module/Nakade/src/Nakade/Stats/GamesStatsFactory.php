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
    const GAMES_BY_POINTS = 'points';
    const GAMES_LOST_ON_TIME = 'time';
    const GAMES_LOST_BY_FORFEIT = 'forfeit';
    const CLOSE_MATCHES = 'close';
    const CLOSE_WINS = 'close_wins';
    const GAMES_ON_BLACK = 'black';
    const GAMES_ON_WHITE = 'white';
    const WIN_ON_BLACK = 'black_win';
    const WIN_ON_WHITE = 'white_win';
    const DEFEAT_ON_BLACK = 'black_defeat';
    const DEFEAT_ON_WHITE = 'white_defeat';
    const DRAW_ON_BLACK = 'black_draw';
    const DRAW_ON_WHITE = 'white_draw';
    const CLOSE_DEFEATS = 'close_defeats';
    const LOST_BY_RESIGN = 'resign';
    const HIGH_WINS = 'high_wins';

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
           case self::GAMES_BY_POINTS:
               $stats = Games\PointGames::getInstance();
               break;
           case self::GAMES_LOST_ON_TIME:
               $stats = Games\TimeLossGames::getInstance();
               break;
           case self::GAMES_LOST_BY_FORFEIT:
               $stats = Games\ForfeitLossGames::getInstance();
               break;
           case self::CLOSE_MATCHES:
               $stats = Games\CloseMatches::getInstance();
               break;
           case self::CLOSE_WINS:
               $stats = Games\CloseWins::getInstance();
               break;
           case self::GAMES_ON_BLACK:
               $stats = Games\BlackGames::getInstance();
               break;
           case self::GAMES_ON_WHITE:
               $stats = Games\WhiteGames::getInstance();
               break;
           case self::WIN_ON_BLACK:
               $stats = Games\BlackWonGames::getInstance();
               break;
           case self::WIN_ON_WHITE:
               $stats = Games\WhiteWonGames::getInstance();
               break;
           case self::DEFEAT_ON_BLACK:
               $stats = Games\BlackDefeatedGames::getInstance();
               break;
           case self::DEFEAT_ON_WHITE:
               $stats = Games\WhiteDefeatedGames::getInstance();
               break;
           case self::DRAW_ON_BLACK:
               $stats = Games\BlackDrawGames::getInstance();
               break;
           case self::DRAW_ON_WHITE:
               $stats = Games\WhiteDrawGames::getInstance();
               break;
           case self::CLOSE_DEFEATS:
               $stats = Games\CloseDefeats::getInstance();
               break;
           case self::LOST_BY_RESIGN:
               $stats = Games\LostByResign::getInstance();
               break;
           case self::HIGH_WINS:
               $stats = Games\HighWins::getInstance();
               break;

            default :
              throw new RuntimeException(sprintf('An unknown stats was provided.'));
        }

         if ($this->getPlayerId() == null) {
            throw new RuntimeException(sprintf('PlayerId has to be set. Found:null'));
         }

        $stats->setMatches($this->getMatches());
        return $stats->getNumberOfGames($this->getPlayerId());
    }
}

