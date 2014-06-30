<?php
namespace Season\Schedule;

/**
 * Description of Schedule
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Schedule {

    const BYE = 'JOKER';
    private $matchDates;
    private $noMatch;
    private $noPlayers=8;
    protected $_week=0;

    /**
     * @param array $matchDates
     */
    public function __construct(array $matchDates)
    {
        $this->matchDates = $matchDates;
    }

    public function makePairingsForLeague($players) {


        shuffle($players);
        $this->noPlayers = count($players);
        $this->noMatch=0;
        if (count($players) % 2 ) {
            array_push($players, self::BYE);
        }

        $pairing   = array();  // Array für den kompletten Spielplan

        for ($round=1; $round<count($players); $round++) {
            $this->moveLastToSecond($players);
            $pairing[$round] = $this->makePairingsForMatchDay($players);
        }
//        ksort($plan); // nach Spieltagen sortieren

        return $pairing;

    }

    /**
     * @param array &$players
     */
    private function moveLastToSecond(array &$players)
    {
        $first = $players[1];
        $last = array_pop($players); //cut last and pop it
        $replacement = array($last,$first);
        array_splice($players, 1, 1, $replacement);
    }

    private function makePairingsForMatchDay($teams)
    {
        $roundPairings = array();
        $isPair=true;
        while (count($teams) > 0) {

            $black =  array_shift($teams);
            $white =  array_pop($teams);
            $pairing = array($black, $white);

            if (in_array(self::BYE, $pairing)) {
                continue;
            }
            $this->noMatch++;

            //change home and away
            if ($this->noMatch%$this->noPlayers!=1 && $isPair) {
                $pairing = $this->changeColors($pairing);
            }

            $roundPairings[] = $pairing;
            $isPair = !$isPair;
        }

        return $roundPairings;
    }


    private function changeColors($pairing)
    {
        $white = array_shift($pairing);
        array_push($pairing, $white);

        return $pairing;
    }

    private function makePairings($plan)
    {
        $pairings = array();
        foreach ($plan as $matchday => $matchdaypairing) {

            //each matchday another date
            $date =  $this->getMatchDate($matchday);

            foreach ($matchdaypairing as $game => $pairing) {

                $data['matchday'] = $matchday;
                $data['league']   = $pairing['away']->getLeague();
                $data['lid']      = $pairing['away']->getLid();
                $data['date']     = $date;
                $data['black']    = $pairing['home']->getPlayer();
                $data['blackId']  = $pairing['home']->getId();
                $data['white']    = $pairing['away']->getPlayer();
                $data['whiteId']  = $pairing['away']->getId();

                $match = new \League\Entity\Match();
                $match->exchangeArray($data);

                array_push($pairings, $match);
            }
        }

        return $pairings;

    }

    private function getMatchDate($week)
    {
        /* @todo: given matchday -> making 3-4 weeks cycle */
       $modify = sprintf('+%s week', $week-1);
       $date = new \DateTime($this->_startdate);

       return $date->modify($modify);

    }

    /**
     * @return mixed
     */
    public function getMatchDates()
    {
        return $this->matchDates;
    }

}
