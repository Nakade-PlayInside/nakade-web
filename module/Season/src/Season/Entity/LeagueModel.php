<?php

namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nakade\TournamentInterface;

/**
 * Class LeagueModel
 *
 * @package Season\Entity
 *
 * @ORM\MappedSuperclass
 */
class LeagueModel implements TournamentInterface
{

  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    protected $id;

   /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Season", cascade={"persist"})
     * @ORM\JoinColumn(name="season", referencedColumnName="id", nullable=false)
     */
    protected $season;

   /**
   *
   * @ORM\Column(name="number", type="integer")
   */
    protected $number;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

}