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
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Association", cascade={"persist"})
     * @ORM\JoinColumn(name="association", referencedColumnName="id", nullable=true)
     */
    private $association;

    /**
     * @ORM\Column(name="subject", type="string", length=60, nullable=false)
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="\Moderator\Entity\SupportMessage", mappedBy="request", cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="processedBy", referencedColumnName="uid", nullable=true)
     */
    private $processedBy;

    /**
     * @ORM\Column(name="startDate", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(name="doneDate", type="datetime", nullable=true)
     */
    private $doneDate;

    /**
     * @ORM\Column(name="isOngoing", type="boolean", nullable=false)
     */
    private $isOngoing=false;

    /**
     * @ORM\Column(name="isDone", type="boolean", nullable=false)
     */
    private $isDone=false;

    /**
     * @ORM\Column(name="isCanceled", type="boolean", nullable=false)
     */
    private $isCanceled=false;

    /**
     * construct
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
     * @param bool $isCanceled
     */
    public function setIsCanceled($isCanceled)
    {
        $this->isCanceled = $isCanceled;
    }

    /**
     * @return bool
     */
    public function isCanceled()
    {
        return $this->isCanceled;
    }

    /**
     * @param bool $isDone
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->isDone;
    }

    /**
     * @param bool $isOngoing
     */
    public function setIsOngoing($isOngoing)
    {
        $this->isOngoing = $isOngoing;
    }

    /**
     * @return bool
     */
    public function isOngoing()
    {
        return $this->isOngoing;
    }

    /**
     * @param User $processedBy
     */
    public function setProcessedBy(User $processedBy)
    {
        $this->processedBy = $processedBy;
    }

    /**
     * @return User
     */
    public function getProcessedBy()
    {
        return $this->processedBy;
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
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
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