<?php
namespace League\Statistics;

/**
 * base class for game statistics.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class GameStats
{
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
     * setter for entities of matches
     *
     * @param array $allMatches
     *
     * @return $this
     */
    public function setMatches(array $allMatches)
    {

        $this->allMatches = $allMatches;
        return $this;
    }

    /**
     * getter for matches
     * @return array
     */
    public function getMatches()
    {
        return $this->allMatches;
    }


}
