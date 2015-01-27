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

    private $temp = array();
    private $matches = array();
    protected static $instances=array();
    protected $allMatches;

    /**
     * Singleton Pattern for preventing object inflation.
     * @return $this
     */
    public static function getInstance()
    {
        $cls = get_called_class(); // late-static-bound class name
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
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