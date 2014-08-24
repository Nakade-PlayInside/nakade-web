<?php
namespace Support\Entity;

use Doctrine\ORM\Mapping as ORM;
use Season\Entity\Association;
use User\Entity\User;

/**
 * Class LeagueManager
 *
 * @package Support\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="referee")
 */
class Referee
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="uid", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="nominationDate", type="datetime", nullable=false)
     */
    private $nominationDate;

    /**
     * @ORM\Column(name="isActive", type="boolean", nullable=false)
     */
    private $isActive=true;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
    * constructor
    */
    public function __construct()
    {
        $this->setNominationDate(new \DateTime());
        $this->setIsActive(true);
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
     * @param User $user
     */
    public function setManager(User $user)
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