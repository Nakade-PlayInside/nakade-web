<?php
namespace League\Statistics;

/**
 * base class for game statistic factory.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class StatsFactory
{
    protected  $allMatches;
    protected  $playerId;

    /**
     * Constructor providing an array of match entities
     * @param array $allMatches
     */
    public function __construct($allMatches)
    {
        $this->allMatches=$allMatches;
    }

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
        return $this->allMatches;
    }


}
