<?php
namespace Season\Schedule;

/**
 * Description of Schedule
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Schedule {

    const BYE = 'FREILOS';
    protected $_startdate;
    protected $_week=0;

    public function __construct($date) {

        $this->_startdate = $date;
    }

    public function makeSchedule($teams) {

        shuffle($teams);

        if (count($teams) % 2 ) {
            array_push($teams, self::BYE);
        }

        $plan       = array();  // Array für den kompletten Spielplan
        $noGame     = 0;        // Zähler für Spielnummer

        for ($round=1; $round<count($teams); $round++) {

          //rearrangement of team array
            //array_pop -> getLastElement and shorten array by element
            //array_splice -> remove first element and replace it by last element followed by first element

          //put the last element at first place
          //array_splice($teams, 1, 1, array(array_pop($teams),$teams[1]));
/**
            $teams = array(1,2,3,4,5);

            for ($i=0; $i<2; $i++) {

                $first = $teams[1];
                $last = array_pop($teams);
                $replacement = array($last,$first);
                array_splice($teams, 1, 1, $replacement);
//1,5,2,3,4
 *
 *
 * $teams = array(1,2,3,4,5);
for ($i=0; $i<2; $i++) {

$this->moveLastToFirst($teams);
}
 *
            }*/

            $this->moveLastToSecond($teams);

                for ($noPairing=0; $noPairing<(count($teams)/2); $noPairing++) {

                    $home =  $teams[$noPairing];
                    $away =  $teams[(count($teams)-1)-$noPairing];

                    if ($home == self::BYE || $away == self::BYE ) {
                        continue;
                    }

                    $noGame++;

                    //change home and away
                    if ( ($noGame%count($teams)==1) && ($noPairing%2==0) ) {
                        $temp = $home;
                        $home = $away;
                        $away = $temp;
                    }

                    $plan[$round][$noGame]['away'] = $away;
                    $plan[$round][$noGame]['home'] = $home;

                }
        }
        ksort($plan); // nach Spieltagen sortieren

        $pairing  = $this->makePairings($plan);
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

}
