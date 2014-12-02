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
        if (is_null($this->champion)) {
            $this->champion = new Championship();
        }
        return $this->champion;
    }

    /**
     * @return Cup
     */
    public function getCup()
    {
        if (is_null($this->cup)) {
            $this->cup = new Cup();
        }
        return $this->cup;
    }

    /**
     * @return Medal
     */
    public function getMedal()
    {
        if (is_null($this->medal)) {
            $this->medal = new Medal();
        }
        return $this->medal;
    }




}