<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeasonModel
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="season")
 */
class Season extends SeasonModel
{

    private $openMatches;
    private $noMatches;
    private $firstMatchDate;
    private $lastMatchDate;

    /**
     * @param \DateTime $firstMatchDate
     */
    public function setFirstMatchDate($firstMatchDate)
    {
        $this->firstMatchDate = $firstMatchDate;
    }

    /**
     * @return \DateTime
     */
    public function getFirstMatchDate()
    {
        return $this->firstMatchDate;
    }

    /**
     * @param \DateTime $lastMatchDate
     */
    public function setLastMatchDate($lastMatchDate)
    {
        $this->lastMatchDate = $lastMatchDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastMatchDate()
    {
        return $this->lastMatchDate;
    }

    /**
     * @param int $noMatches
     */
    public function setNoMatches($noMatches)
    {
        $this->noMatches = $noMatches;
    }

    /**
     * @return int
     */
    public function getNoMatches()
    {
        return $this->noMatches;
    }

    /**
     * @param int $openMatches
     */
    public function setOpenMatches($openMatches)
    {
        $this->openMatches = $openMatches;
    }

    /**
     * @return int
     */
    public function getOpenMatches()
    {
        return $this->openMatches;
    }

    /**
     * @return bool
     */
    public function isReady()
    {
        //@todo: have all registered leagues also matches
        return false;
    }

    /**
     * @return bool
     */
    public function hasStarted()
    {
        return $this->getFirstMatchDate() >= new \DateTime();

    }

    /**
     * @return bool
     */
    public function hasEnded()
    {
        return empty($this->openMatches);
    }

}