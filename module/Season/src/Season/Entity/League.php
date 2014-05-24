<?php

namespace Season\Entity;

use League\Entity\Season;
use Doctrine\ORM\Mapping as ORM;

/**
 * * Class League
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="league")
 */
class League
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
  /**
   * @ORM\ManyToOne(targetEntity="Season\Entity\Season", cascade={"persist"})
   * @ORM\JoinColumn(name="season", referencedColumnName="id", nullable=false)
   */
   private $season;

  /**
   * @ORM\Column(name="number", type="integer")
   */
   private $number;

  /**
   * @ORM\Column(name="name", type="string")
   */
   private $name;

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