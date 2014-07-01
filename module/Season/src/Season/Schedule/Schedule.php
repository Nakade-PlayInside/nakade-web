<?php
namespace Season\Schedule;

/**
 * Class Schedule
 *
 * @package Season\Schedule
 */
class Schedule
{

    const BYE = 'JOKER';
    private $matchDates;
    private $noMatch;
    protected $_week=0;

    /**
     * @param array $matchDates
     */
    public function __construct(array $matchDates)
    {
        $this->matchDates = $matchDates;
    }

    /**
     * pairings of a league with balanced color, randomized order and using a bye if impair
     *
     * @param array $players
     *
     * @return array
     */
    private function makePairingsForLeague(array $players) {

        $pairing = array();
        shuffle($players);
        $this->noMatch=0;

        if (count($players) % 2 ) {
            array_push($players, self::BYE);
        }

        for ($round=1; $round<count($players); $round++) {
            $pairing[$round] = $this->makePairingsForMatchDay($players);
            $this->moveLastToSecond($players);
        }
//        ksort($plan); // nach Spieltagen sortieren

        return $pairing;

    }

    /**
     * League system s. https://de.wikipedia.org/wiki/Spielplan_(Sport)
     *
     * @param array &$players
     */
    private function moveLastToSecond(array &$players)
    {
        $first = $players[1];
        $last = array_pop($players); //cut last and pop it
        $replacement = array($last,$first);
        array_splice($players, 1, 1, $replacement);
    }

    /**
     * @param array $players
     *
     * @return array
     */
    private function makePairingsForMatchDay(array $players)
    {
        $teams = $players;
        $roundPairings = array();
        $no=0;

        while (count($teams) > 0) {

            $this->noMatch++;
            $no++;

            $black =  array_shift($teams);
            $white =  array_pop($teams);
            $pairing = array($black, $white);

            if ($this->hasToChangeColor($players, $no)) {
                $pairing = $this->changeColors($pairing);
            }

            if (!in_array(self::BYE, $pairing)) {
                $roundPairings[] = $pairing;
            }
        }

        return $roundPairings;
    }

    /**
     * balanced pairing for players having black.
     *
     * @param array $players
     * @param int   $no
     *
     * @return bool
     */
    private function hasToChangeColor(array $players, $no)
    {
        $noMatch = $this->getNoMatch();
        $noPlayers = count($players);

        if (in_array(self::BYE, $players)) {
            $noRealPlayers = $noPlayers-1;
            return ($no%2 && $noMatch%$noPlayers!=1 || $noMatch%$noRealPlayers==0);
        }

        return $noMatch%$noPlayers!=1;
    }

    /**
     * @param array $pairing
     *
     * @return array
     */
    private function changeColors(array $pairing)
    {
        $white = array_shift($pairing);
        array_push($pairing, $white);

        return $pairing;
    }

    /**
     * @return mixed
     */
    public function getMatchDates()
    {
        return $this->matchDates;
    }

    /**
     * @return int
     */
    public function getNoMatch()
    {
        return $this->noMatch;
    }

}
