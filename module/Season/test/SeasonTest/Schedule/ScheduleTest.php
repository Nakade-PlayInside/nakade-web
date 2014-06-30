<?php
namespace Season\Schedule;

use PHPUnit_Framework_TestCase;

/**
 * Class ScheduleTest
 *
 * @package Season\Schedule
 */
class ScheduleTest extends PHPUnit_Framework_TestCase
{

    private $matchDates;
    private $object;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->matchDates =  array (1,2,3);
        $this->object = new Schedule($this->matchDates);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object     Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod($object, $methodName, array $parameters)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }


    /**
     * changeColors
     */
    public function testChangeColors()
    {
        $test = array(1, 2);

        $pairing = $this->invokeMethod($this->getObject(), 'changeColors', array($test));
        $this->assertSame(2, $pairing[0]);
    }

    /**
     * number of matches by pair amount of players for a single match day
     */
    public function testNumberOfMatchesByPairPairings()
    {
        $test = array(1,2,3,4,5,6,7,8);

        $res = $this->invokeMethod($this->getObject(), 'makePairingsForMatchDay', array($test));
        $this->assertSame(4, count($res));
    }

    /**
     * number of matches by impair amount of players for a single match day
     */
    public function testNumberOfMatchesByImpairPairings()
    {
        $test = array(1,2,3,4,5,6,7,Schedule::BYE);

        $res = $this->invokeMethod($this->getObject(), 'makePairingsForMatchDay', array($test));
        $this->assertSame(3, count($res));
    }

    /**
     * expected pairing and its color changed
     */
    public function testExpectedPairingByPairPairings()
    {
        $test = array(1,2,3,4,5,6,7,8);

        $res = $this->invokeMethod($this->getObject(), 'makePairingsForMatchDay', array($test));

        $player = array();
        foreach ($res as $pairing) {
            $player = array_merge($player, $pairing);
        }
        $msg = sprintf("duplicate match found");
        $this->assertTrue(count(array_unique($player))==count($test), $msg); //no duplicates
    }


    /**
     * number of match days
     */
    public function testNumberOfMatchDaysForSingleLeague()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));
        $matchDays = $this->getNumberOfMatchDays($test);

        $this->assertSame($matchDays, count($res));
    }

    /**
     * number of match days using bye
     */
    public function testNumberOfMatchDaysForSingleLeagueWithImpairNoOfPlayers()
    {
        $test = array(1,2,3,4,5,6,7);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));
        $matchDays = $this->getNumberOfMatchDays($test);

        $this->assertSame($matchDays, count($res));
    }

    /**
     * total number of matches in a league
     */
    public function testNumberOfMatchesForSingleLeague()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        $matchDays = $this->getNumberOfMatchDays($test);
        $paringsPerMatchDay = count($test)/2;
        $totalMatches = $matchDays*$paringsPerMatchDay;

        $numberOfMatches=0;
        foreach ($res as $matchDayPairings) {
            $numberOfMatches += count($matchDayPairings);
        }

        $this->assertSame($totalMatches, $numberOfMatches);
    }

    /**
     * total number of matches in a league
     */
    public function testNumberOfMatchesForSingleLeagueUsingBye()
    {
        $test = array(1,2,3,4,5,6,7,Schedule::BYE);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        $matchDays = $this->getNumberOfMatchDays($test);
        $paringsPerMatchDay = count($test)/2 - 1;
        $totalMatches = $matchDays*$paringsPerMatchDay;

        $numberOfMatches=0;
        foreach ($res as $matchDayPairings) {
            $numberOfMatches += count($matchDayPairings);
        }

        $this->assertSame($totalMatches, $numberOfMatches);
    }

    /**
     * number of match days in a league
     */
    public function testNumberOfMatchDays()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        $matchDays = $this->getNumberOfMatchDays($test);
        $this->assertSame($matchDays, count($res));
    }

    /**
     * each player's number of matches
     */
    public function testNumberOfMatchesForEachPlayer()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        foreach ($test as $player) {
            $no=0;
            foreach ($res as $matchDays) {
                foreach ($matchDays as $match) {
                    if (in_array($player, $match)) {
                        $no++;
                    }
                }
            }
            $this->assertSame(count($test)-1, $no); //no of matches
        }
    }

    /**
     * each player's number of matches using bye
     */
    public function testNumberOfMatchesForEachPlayerUsingBye()
    {
        $test = array(1,2,3,4,5,6,7);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        foreach ($test as $player) {
            $no=0;
            foreach ($res as $matchDays) {
                foreach ($matchDays as $match) {
                    if (in_array($player, $match)) {
                        $no++;
                    }
                }
            }
            $this->assertSame(count($test)-1, $no); //no of matches
        }
    }


    /**
     * playing each opponent
     */
    public function testMatchingAllOpponents()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        foreach ($test as $player) {
            $opponents=array();
            foreach ($res as $matchDays) {
                foreach ($matchDays as $match) {
                    if (in_array($player, $match)) {

                        $black = array_shift($match);
                        $white = array_pop($match);
                        if ($player == $black) {
                            $opponents[] = $white;
                        } else {
                            $opponents[] = $black;
                        }
                    }
                }
            }
            asort($opponents);
            $msg = sprintf('duplicate opponents found: %s', implode($opponents, ","));
            $this->assertTrue(count(array_unique($opponents))==count($opponents), $msg); //no duplicates
        }

    }

    /**
     * playing each opponent using bye
     */
    public function testMatchingAllOpponentsUsingBye()
    {
        $test = array(1,2,3,4,5,6,7);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        foreach ($test as $player) {
            $opponents=array();
            foreach ($res as $matchDays) {
                foreach ($matchDays as $match) {
                    if (in_array($player, $match)) {

                        $black = array_shift($match);
                        $white = array_pop($match);
                        if ($player == $black) {
                            $opponents[] = $white;
                        } else {
                            $opponents[] = $black;
                        }
                    }
                }
            }
            asort($opponents);
            $msg = sprintf('duplicate opponents found: %s', implode($opponents, ","));
            $this->assertTrue(count(array_unique($opponents))==count($opponents), $msg); //no duplicates
        }

    }

    /**
     * correct of number of matches playing black and white
     */
    public function testCorrectAmountOfColor()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        foreach ($test as $player) {
            $noWhite=0;
            $noBlack=0;
            foreach ($res as $matchDays) {
                foreach ($matchDays as $match) {
                    if (in_array($player, $match)) {

                        $black = array_shift($match);
                        $white = array_pop($match);
                        if ($player == $black) {
                            $noBlack++;
                        } elseif ($player == $white) {
                            $noWhite++;
                        }
                    }
                }
            }
            $isCorrect = $noBlack>2 && $noBlack<5 && $noWhite>2 && $noWhite<5;
            $msg = sprintf('no correct alternating colors of player %s', $player);
            $this->assertTrue($isCorrect, $msg); //no of black matches

            if ($noBlack == 3) {
                $this->assertSame(4, $noWhite);
            } elseif ($noWhite == 3) {
                $this->assertSame(4, $noBlack);
            }
        }

    }

    /**
     * correct of number of matches playing black and white
     */
    public function testCorrectAmountOfColorUsingBye()
    {
        $test = array(1,2,3,4,5,6,7);
        $res = $this->invokeMethod($this->getObject(), 'makePairingsForLeague', array($test));

        foreach ($test as $player) {
            $noWhite=0;
            $noBlack=0;
            foreach ($res as $matchDays) {
                foreach ($matchDays as $match) {
                    if (in_array($player, $match)) {

                        $black = array_shift($match);
                        $white = array_pop($match);
                        if ($player == $black) {
                            $noBlack++;
                        } elseif ($player == $white) {
                            $noWhite++;
                        }
                    }
                }
            }
            $msg = sprintf('player %s playing white: %d - black: %d', $player, $noWhite, $noBlack);
            $this->assertSame(3, $noWhite, $msg);
            $this->assertSame(3, $noBlack, $msg);

        }

    }


    /**
     * @param array $players
     *
     * @return int
     */
    public function getNumberOfMatchDays(array $players)
    {
        if (count($players)%2 == 0) {
            return count($players)-1;
        }
        return count($players);
    }

    /**
     * @return array
     */
    public function getMatchDates()
    {
        return $this->matchDates;
    }

    /**
     * @return Schedule
     */
    public function getObject()
    {
        return $this->object;
    }



}