<?php
namespace Moderator\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Season\Entity\Association;

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
     * @ORM\ManyToOne(targetEntity="\Moderator\Entity\SupportSubject", cascade={"persist"})
     * @ORM\JoinColumn(name="subject", referencedColumnName="id", nullable=false)
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="\Moderator\Entity\SupportMessage", mappedBy="request", cascade={"persist", "remove"})
     */
    private $messages;

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
     * @return \User\Entity\User
     */
    public function getRequester()
    {
        return $this->getMessages()->first()->getAuthor();
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