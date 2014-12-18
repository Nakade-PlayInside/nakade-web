<?php
namespace Stats\Entity;

/**
 * Class Certificate
 *
 * @package Stats\Entity
 */
class Certificate
{
    private $tournamentInfo;
    private $name;
    private $award;

    /**
     * @param string $award
     */
    public function setAward($award)
    {
        $this->award = $award;
    }

    /**
     * @return string
     */
    public function getAward()
    {
        return $this->award;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $tournamentInfo
     */
    public function setTournamentInfo($tournamentInfo)
    {
        $this->tournamentInfo = $tournamentInfo;
    }

    /**
     * @return string
     */
    public function getTournamentInfo()
    {
        return $this->tournamentInfo;
    }

    /**
     * @return bool
     */
    public function hasAward()
    {
        return !empty($this->award);
    }


}