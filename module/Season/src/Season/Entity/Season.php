<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Season
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="season")
 */
class Season
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
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Title", cascade={"persist"})
     * @ORM\JoinColumn(name="title", referencedColumnName="id", nullable=false)
     */
    private $title;

  /**
   * @ORM\Column(name="number", type="integer", nullable=false)
   */
    private $number;

  /**
   * @ORM\Column(name="startDate", type="date", nullable=false)
   */
    private $startDate;

   /**
   * @ORM\Column(name="winPoints", type="integer", nullable=false)
   */
    private $winPoints;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\TieBreaker", cascade={"persist"})
     * @ORM\JoinColumn(name="tieBreaker1", referencedColumnName="id", nullable=false)
     */
    private $tieBreaker1;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\TieBreaker", cascade={"persist"})
     * @ORM\JoinColumn(name="tieBreaker2", referencedColumnName="id", nullable=false)
     */
    private $tieBreaker2;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\TieBreaker", cascade={"persist"})
     * @ORM\JoinColumn(name="tieBreaker3", referencedColumnName="id", nullable=false)
     */
    private $tieBreaker3;

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
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param TieBreaker $tieBreaker1
     */
    public function setTieBreaker1(TieBreaker $tieBreaker1)
    {
        $this->tieBreaker1 = $tieBreaker1;
    }

    /**
     * @return TieBreaker
     */
    public function getTieBreaker1()
    {
        return $this->tieBreaker1;
    }

    /**
     * @param TieBreaker $tieBreaker2
     */
    public function setTieBreaker2(TieBreaker $tieBreaker2)
    {
        $this->tieBreaker2 = $tieBreaker2;
    }

    /**
     * @return TieBreaker
     */
    public function getTieBreaker2()
    {
        return $this->tieBreaker2;
    }

    /**
     * @param TieBreaker $tieBreaker3
     */
    public function setTieBreaker3(TieBreaker $tieBreaker3)
    {
        $this->tieBreaker3 = $tieBreaker3;
    }

    /**
     * @return TieBreaker
     */
    public function getTieBreaker3()
    {
        return $this->tieBreaker3;
    }

    /**
     * @param Title $title
     */
    public function setTitle(Title $title)
    {
        $this->title = $title;
    }

    /**
     * @return Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $winPoints
     */
    public function setWinPoints($winPoints)
    {
        $this->winPoints = $winPoints;
    }

    /**
     * @return int
     */
    public function getWinPoints()
    {
        return $this->winPoints;
    }

}