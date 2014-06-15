<?php
namespace League\Standings;

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
     * @param array $allMatches
     */
    public function setMatches(array $allMatches)
    {
        $this->allMatches = $allMatches;
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
