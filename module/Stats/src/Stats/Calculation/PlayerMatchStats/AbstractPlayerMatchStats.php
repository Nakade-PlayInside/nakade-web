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
abstract class AbstractPlayerMatchStats implements PlayerMatchStatsInterface
{

    private $temp;
    private $matches;

    /**
     * has to be a constructor prior to static because of the necessity to reset all vars in factory
     */
    public function __constructor()
    {
        $this->temp = array();
        $this->matches = array();
    }

    /**
     * @param Match $match
     */
    public function addMatch(Match $match)
    {
        $this->temp[] = $match;
        $this->exchangeData();
    }

    /**
     * @return $this
     */
    public function resetMatches()
    {
        $this->temp = array();
        return $this;
    }

    /**
     * @return bool
     */
    private function isGreaterOrEqual()
    {
        return (count($this->temp) >= count($this->matches));
    }

    /**
     * @return bool
     */
    private function exchangeData()
    {
        if ($this->isGreaterOrEqual()) {
            $this->matches = $this->temp;
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return string
     */
    abstract public function getName();

}