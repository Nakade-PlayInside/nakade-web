<?php
namespace Nakade\Stats;

/**
 * base class for game statistic factory.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class StatsFactory implements StatsFactoryInterface
{
    protected  $matches;
    protected  $playerId;


    /**
     * set the playerId
     * @param int $playerId
     *
     * @return $this
     */
    public function setPlayerId($playerId)
    {
        $this->playerId=$playerId;
        return $this;
    }

    /**
     * get the playerID
     * @return int
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }


}
