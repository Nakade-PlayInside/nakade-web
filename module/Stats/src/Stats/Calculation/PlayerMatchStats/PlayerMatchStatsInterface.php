<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation\PlayerMatchStats;

use Season\Entity\Match;

/**
 * Class AbstractMatchStats
 *
 * @package Stats\Calculation\PlayerMatchStats
 */
Interface PlayerMatchStatsInterface
{

    /**
     * @param Match $match
     */
    public function addMatch(Match $match);

    /**
     * @return $this
     */
    public function resetMatches();

    /**
     * @return array
     */
    public function getMatches();

    /**
     * @return string
     */
    public function getName();

}