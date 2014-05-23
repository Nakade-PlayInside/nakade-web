<?php
namespace Season\Entity;


use League\Entity\League;
use User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatchModel
 *
 * @package Season\Entity
 *
 * @ORM\MappedSuperclass
 */
class MatchModel
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
     * @ORM\ManyToOne(targetEntity="League\Entity\Result", cascade={"persist"})
     * @ORM\JoinColumn(name="result", referencedColumnName="id")
     */
    private $result;

   /**
   * @ORM\Column(name="points", type="float")
   */
    private $points;

   /**
   * @ORM\Column(name="date", type="datetime")
   */
    private $date;

   /**
   * @ORM\ManyToOne(targetEntity="Season\Entity\League", cascade={"persist"})
   * @ORM\JoinColumn(name="league", referencedColumnName="id", nullable=false)
   */
    private $league;


   /**
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="black", referencedColumnName="uid", nullable=false)
   */
    private $black;

   /**
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="white", referencedColumnName="uid", nullable=false)
   */
    private $white;

   /**
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="winner", referencedColumnName="uid")
   */
    private $winner;

   /**
   * @ORM\Column(name="matchday", type="integer")
   */
    private $matchDay;

    /**
     * @param User $black
     */
    public function setBlack(User $black)
    {
        $this->black = $black;
    }

    /**
     * @return User
     */
    public function getBlack()
    {
        return $this->black;
    }

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
     * @param League $league
     */
    public function setLeague(League $league)
    {
        $this->league = $league;
    }

    /**
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param int $matchDay
     */
    public function setMatchDay($matchDay)
    {
        $this->matchDay = $matchDay;
    }

    /**
     * @return int
     */
    public function getMatchDay()
    {
        return $this->matchDay;
    }

    /**
     * @param float $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return float
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param User $white
     */
    public function setWhite(User $white)
    {
        $this->white = $white;
    }

    /**
     * @return User
     */
    public function getWhite()
    {
        return $this->white;
    }

    /**
     * @param User $winner
     */
    public function setWinner(User $winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return null|User
     */
    public function getWinner()
    {
        return $this->winner;
    }


}