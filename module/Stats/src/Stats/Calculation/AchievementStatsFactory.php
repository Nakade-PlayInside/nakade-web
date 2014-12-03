<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation;

use Season\Entity\League;
use Stats\Entity\AbstractPrize;
use Stats\Entity\Championship;
use Stats\Entity\Cup;
use Stats\Entity\Medal;

/**
 * Class AchievementStatsFactory
 *
 * @package Stats\Calculation
 */
class AchievementStatsFactory {

    private $tournament;
    private $champion;
    private $medal;
    private $cup;

    /**
     * constructor
     */
    public function __construct()
    {

        $this->champion = new Championship();
        $this->medal = new Medal();
        $this->cup = new Cup();
    }

    /**
     * @param League $tournament
     * @param $position
     */
    public function evalAchievement(League $tournament, $position)
    {
        $this->setTournament($tournament);
        $prize = $this->getCup();

        if ($tournament->getNumber() == 1) {
            $prize = $this->getChampion();
        } elseif ($tournament->getNumber()>1) {
            $prize = $this->getMedal();
        }
        $this->evalPrizes($prize, $position);
    }

    private function evalPrizes(AbstractPrize $prize, $position)
    {
        switch($position) {
            case 1: $prize->addGold($this->getTournament());
                break;
            case 2: $prize->addSilver($this->getTournament());
                break;
            case 3: $prize->addBronze($this->getTournament());
                break;
        }
    }

    /**
     * @return \Stats\Entity\Championship
     */
    public function getChampion()
    {
        return $this->champion;
    }

    /**
     * @return \Stats\Entity\Cup
     */
    public function getCup()
    {
        return $this->cup;
    }

    /**
     * @return \Stats\Entity\Medal
     */
    public function getMedal()
    {
        return $this->medal;
    }

    /**
     * @param League $tournament
     *
     * @return $this
     */
    public function setTournament(League $tournament)
    {
        $this->tournament = $tournament;
        return $this;
    }

    /**
     * @return League
     */
    public function getTournament()
    {
        return $this->tournament;
    }



}