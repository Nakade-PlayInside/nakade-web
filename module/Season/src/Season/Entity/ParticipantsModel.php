<?php
namespace Season\Entity;

use League\Entity\League;
use User\Entity\User;
use League\Entity\Season;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class ParticipantsModel
 *
 * @package Season\Entity
 *
 * @ORM\MappedSuperclass
 */
class ParticipantsModel
{
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="pid", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="\Season\Entity\Season", cascade={"persist"})
   * @ORM\JoinColumn(name="season", referencedColumnName="id")
   */
  protected $league;

  /**
   * @ORM\ManyToOne(targetEntity="\Season\Entity\Season", cascade={"persist"})
   * @ORM\JoinColumn(name="season", referencedColumnName="id", nullable=false)
   */
  protected $season;

 /**
  * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
  * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
  */
  protected $user;

  /**
   * @ORM\Column(name="date", type="datetime", nullable=false)
   */
  protected $date;

  /**
   * @ORM\Column(name="acceptString", type="text", nullable=false)
   */
  protected $acceptString;

    /**
     * @ORM\Column(name="hasAccepted", type="boolean", nullable=false)
     */
  protected $hasAccepted;

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

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $acceptString
     */
    public function setAcceptString($acceptString)
    {
        $this->acceptString = $acceptString;
    }

    /**
     * @return string
     */
    public function getAcceptString()
    {
        return $this->acceptString;
    }

    /**
     * @param bool $hasAccepted
     */
    public function setHasAccepted($hasAccepted)
    {
        $this->hasAccepted = $hasAccepted;
    }

    /**
     * @return bool
     */
    public function hasAccepted()
    {
        return $this->hasAccepted;
    }

    /**
     * @param \DateTime $date
     */
    public function setInvitationDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getInvitationDate()
    {
        return $this->date;
    }


}