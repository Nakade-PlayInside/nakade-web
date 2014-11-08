<?php
namespace Nakade\Stats;

/**
 * Interface GameStatsInterface
 *
 * @package Nakade\Stats
 */
interface StatsFactoryInterface
{

    /**
     * Constructor providing an array of match entities
     * @param array $matches
     */
    public function __construct(array $matches);

    /**
     * set the playerId
     * @param int $playerId
     *
     * @return $this
     */
    public function setPlayerId($playerId);

    /**
     * get the playerID
     * @return int
     */
    public function getPlayerId();

    /**
     * @return array
     */
    public function getMatches();

}
