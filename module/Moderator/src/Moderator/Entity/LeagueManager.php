<?php
namespace Moderator\Entity;

use Doctrine\ORM\Mapping as ORM;
use Season\Entity\Association;
use User\Entity\User;

/**
 * Class LeagueManager
 *
 * @package Moderator\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueManager")
 */
class LeagueManager
{//todo: composite key of manager and association

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="manager", referencedColumnName="uid", nullable=false)
     */
    private $manager;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Association", cascade={"persist"})
     * @ORM\JoinColumn(name="association", referencedColumnName="id", nullable=false)
     */
    private $association;

    /**
     * @ORM\Column(name="nominationDate", type="datetime", nullable=false)
     */
    private $nominationDate;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="nominatedBy", referencedColumnName="uid", nullable=false)
     */
    private $nominatedBy;

    /**
     * @ORM\Column(name="isActive", type="boolean", nullable=false)
     */
    private $isActive=true;

    /**
     * @param Association $association
     */
    public function setAssociation(Association $association)
    {
        $this->association = $association;
    }

    /**
     * @return Association
     */
    public function getAssociation()
    {
        return $this->association;
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
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return boolean
     */
    public function IsActive()
    {
        return $this->isActive;
    }

    /**
     * @param User $manager
     */
    public function setManager(User $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param User $nominatedBy
     */
    public function setNominatedBy(User $nominatedBy)
    {
        $this->nominatedBy = $nominatedBy;
    }

    /**
     * @return User
     */
    public function getNominatedBy()
    {
        return $this->nominatedBy;
    }

    /**
     * @param \DateTime $nominationDate
     */
    public function setNominationDate($nominationDate)
    {
        $this->nominationDate = $nominationDate;
    }

    /**
     * @return \DateTime
     */
    public function getNominationDate()
    {
        return $this->nominationDate;
    }



}