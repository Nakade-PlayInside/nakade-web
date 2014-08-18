<?php
namespace Moderator\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Season\Entity\Association;
use User\Entity\User;

/**
 * Class SupportRequest
 *
 * @package Moderator\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="supportRequest")
 */
class SupportRequest
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Moderator\Entity\SupportType", cascade={"persist"})
     * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="creator", referencedColumnName="uid", nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Association", cascade={"persist"})
     * @ORM\JoinColumn(name="association", referencedColumnName="id", nullable=true)
     */
    private $association;

    /**
     * @ORM\ManyToOne(targetEntity="\Moderator\Entity\SupportSubject", cascade={"persist"})
     * @ORM\JoinColumn(name="subject", referencedColumnName="id", nullable=false)
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="\Moderator\Entity\SupportMessage", mappedBy="request", cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * @ORM\Column(name="createDate", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @ORM\Column(name="startDate", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(name="doneDate", type="datetime", nullable=true)
     */
    private $doneDate;

    /**
     * @ORM\ManyToOne(targetEntity="\Moderator\Entity\SupportStage", cascade={"persist"})
     * @ORM\JoinColumn(name="stage", referencedColumnName="id", nullable=false)
     */
    private $stage;

    /**
     * @param SupportType $type
     * @param User        $creator
     */
    public function __construct(SupportType $type, User $creator)
    {
        $this->messages = new ArrayCollection();
        $this->setType($type);
        $this->setCreator($creator);
        $this->setCreateDate(new \DateTime());
    }

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
     * @param User $creator
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }


    /**
     * @param \DateTime $doneDate
     */
    public function setDoneDate($doneDate)
    {
        $this->doneDate = $doneDate;
    }

    /**
     * @return \DateTime
     */
    public function getDoneDate()
    {
        return $this->doneDate;
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
     * @param SupportSubject $subject
     */
    public function setSubject(SupportSubject $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return SupportSubject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param SupportType $type
     */
    public function setType(SupportType $type)
    {
        $this->type = $type;
    }

    /**
     * @return SupportType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param SupportStage $stage
     */
    public function setStage(SupportStage $stage)
    {
        $this->stage = $stage;
    }

    /**
     * @return SupportStage
     */
    public function getStage()
    {
        return $this->stage;
    }


    /**
     * @param SupportMessage $message
     */
    public function addMessage(SupportMessage $message)
    {
        $this->messages[] = $message;
        $message->setRequest($this);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

}