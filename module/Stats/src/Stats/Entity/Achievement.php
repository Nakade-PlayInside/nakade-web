<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Achievement
 *
 * @package Stats\Entity
 */
class Achievement
{
    protected $champion;
    protected $cup;
    protected $medal;

    /**
     * @return Championship
     */
    public function getChampion()
    {
        return $this->champion;
    }

    /**
     * @return Cup
     */
    public function getCup()
    {
        return $this->cup;
    }

    /**
     * @return Medal
     */
    public function getMedal()
    {
        return $this->medal;
    }

    /**
     * @param Championship $champion
     */
    public function setChampion(Championship $champion)
    {
        $this->champion = $champion;
    }

    /**
     * @param Cup $cup
     */
    public function setCup(Cup $cup)
    {
        $this->cup = $cup;
    }

    /**
     * @param Medal $medal
     */
    public function setMedal(Medal $medal)
    {
        $this->medal = $medal;
    }

    /**
     * @return bool
     */
    public function hasAchievement()
    {
        return !empty($this->champion) && !empty($this->cup) && !empty($this->medal);
    }




}