<?php
namespace League\Statistics\Games;

use League\Statistics\StatsFactory;
use RuntimeException;

/**
 * This factory provides you the several game stats, e.g. number of
 * won, lost, draw games etc.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class GamesStatsFactory extends StatsFactory
{

    private $gameStats;

    /**
     * receives the points of the provided game statistics
     * @param string $typ
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function getPoints($typ)
    {

        switch (strtolower($typ)) {

           case "played":   $this->gameStats = PlayedGames::getInstance();
               break;

           case "lost"  :   $this->gameStats = LostGames::getInstance();
               break;

           case "won":      $this->gameStats = WonGames::getInstance();
               break;

           case "draw":     $this->gameStats = DrawGames::getInstance();
               break;

           case "suspended":$this->gameStats = SuspendedGames::getInstance();
               break;

           default      :
               throw new RuntimeException(
                   sprintf('An unknown tiebreaker was provided.')
               );
        }

        $matches = $this->getMatches();
        $this->gameStats->setMatches($matches);

         if ($this->getPlayerId() == null) {
            throw new RuntimeException(
                sprintf('PlayerId has to be set. Found:null')
            );
         }

        return $this->gameStats->getNumberOfGames($this->getPlayerId());
    }
}

?>
