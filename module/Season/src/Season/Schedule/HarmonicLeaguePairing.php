<?php
namespace Season\Schedule;

use Season\Services\RepositoryService;

/**
 * Class HarmonicLeaguePairing
 *
 * This pairing algorithm is based on the key index system of the harmonic key scheme of the DFB (11.06.2003), see
 * http://portal.dfbnet.org/fileadmin/content/downloads/faq/SZ_1-L.pdf , commonly known as sliding system.
 * The sliding system procedure comes clear if shown graphically (https://de.wikipedia.org/wiki/Spielplan_(Sport)) .
 * This algorithm is considering playing each opponent just once a season. Further more the usage of a bye for impair
 * amount of participants is adopted. Therefore, the balance of color (black or white) is considered.
 *
 * @package Season\Schedule
 */
class HarmonicLeaguePairing
{

    const BYE = 'JOKER';
    private $noMatch;


    /**
     * Get pairings of players of a single league. Index of the returned array is the number of match day
     *
     * @param array $players
     *
     * @return array
     */
    public function getPairings(array $players)
    {

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
     * @return int
     */
    public function getNoMatch()
    {
        return $this->noMatch;
    }

}
