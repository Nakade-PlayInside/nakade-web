<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a MatchDay
 *
 * @ORM\Entity
 * @ORM\Table(name="matchDay")
 */
class MatchDay
{

  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
   private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Season", cascade={"persist"})
     * @ORM\JoinColumn(name="season", referencedColumnName="id", nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(name="matchDay", type="integer")
     */
    private $matchDay;

   /**
   * @ORM\Column(name="date", type="datetime")
   */
    private $date;

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $matchDay
     */
    public function setMatchDay($matchDay)
    {
        $this->matchDay = $matchDay;
    }

    /**
     * @return mixed
     */
    public function getMatchDay()
    {
        return $this->matchDay;
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