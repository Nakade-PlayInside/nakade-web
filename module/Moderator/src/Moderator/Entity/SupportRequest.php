<?php
namespace Moderator\Entity;

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
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="requestedBy", referencedColumnName="uid", nullable=false)
     */
    private $requestedBy;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Association", cascade={"persist"})
     * @ORM\JoinColumn(name="association", referencedColumnName="id", nullable=false)
     */
    private $association;

    /**
     * @ORM\Column(name="subject", type="string", length=60, nullable=false)
     */
    private $subject;

    /**
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="processedBy", referencedColumnName="uid", nullable=false)
     */
    private $processedBy;

    /**
     * @ORM\Column(name="requestDate", type="datetime", nullable=false)
     */
    private $requestDate;

    /**
     * @ORM\Column(name="acceptingDate", type="datetime", nullable=true)
     */
    private $acceptingDate;

    /**
     * @ORM\Column(name="doneDate", type="datetime", nullable=true)
     */
    private $doneDate;

    /**
     * @ORM\Column(name="isCanceled", type="boolean", nullable=false)
     */
    private $isCanceled=false;

    /**
     * @param \DateTime $acceptingDate
     */
    public function setAcceptingDate($acceptingDate)
    {
        $this->acceptingDate = $acceptingDate;
    }

    /**
     * @return \DateTime
     */
    public function getAcceptingDate()
    {
        return $this->acceptingDate;
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
    public function IsCanceled()
    {
        return $this->isCanceled;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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
     * @param \DateTime $requestDate
     */
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;
    }

    /**
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * @param User $requestedBy
     */
    public function setRequestedBy(User $requestedBy)
    {
        $this->requestedBy = $requestedBy;
    }

    /**
     * @return User
     */
    public function getRequestedBy()
    {
        return $this->requestedBy;
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



}