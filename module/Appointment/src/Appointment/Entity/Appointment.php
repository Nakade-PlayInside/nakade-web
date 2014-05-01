<?php

namespace Appointment\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;
use League\Entity\Match;

/**
 * model
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueAppointments")
 */
class Appointment
{
   /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var \League\Entity\Match
   *
   * @ORM\ManyToOne(targetEntity="League\Entity\Match", cascade={"persist"})
   * @ORM\JoinColumn(name="match", referencedColumnName="id", nullable=false)
   *
   */
  private $match;

  /**
   * @var \User\Entity\User
   *
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="submitter", referencedColumnName="uid", nullable=false)
   *
   */
  private $submitter;

  /**
  * @var \User\Entity\User
  *
  * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
  * @ORM\JoinColumn(name="responder", referencedColumnName="uid", nullable=false)
  *
  */
  private $responder;

  /**
   * @ORM\Column(name="requestDate", type="datetime", nullable=false)
   */
  private $submitDate;

  /**
   * @ORM\Column(name="oldDate", type="datetime", nullable=false)
   */
  private $oldDate;

  /**
   * @ORM\Column(name="appointmentDate", type="datetime", nullable=false)
   */
  private $appointmentDate;

 /**
  * @ORM\Column(name="isConfirmed", type="boolean", nullable=true)
  */
  private $isConfirmed;

  /**
   * @ORM\Column(name="reason", type="text", nullable=true)
   */
  private $reason;

    /**
     * @param \DateTime $appointmentDate
     *
     * @return $this
     */
    public function setAppointmentDate($appointmentDate)
    {
        $this->appointmentDate = $appointmentDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAppointmentDate()
    {
        return $this->appointmentDate;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param bool $isConfirmed
     *
     * @return $this
     */
    public function setConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * @param Match $match
     *
     * @return $this
     */
    public function setMatch(Match $match)
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @return \League\Entity\Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param \DateTime $oldDate
     *
     * @return $this
     */
    public function setOldDate($oldDate)
    {
        $this->oldDate = $oldDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOldDate()
    {
        return $this->oldDate;
    }

    /**
     * @param string $reason
     *
     * @return $this
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param User $responder
     *
     * @return $this
     */
    public function setResponder(User $responder)
    {
        $this->responder = $responder;
        return $this;
    }

    /**
     * @return \User\Entity\User
     */
    public function getResponder()
    {
        return $this->responder;
    }

    /**
     * @param \DateTime $submitDate
     *
     * @return $this
     */
    public function setSubmitDate($submitDate)
    {
        $this->submitDate = $submitDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubmitDate()
    {
        return $this->submitDate;
    }

    /**
     * @param User $submitter
     *
     * @return $this
     */
    public function setSubmitter(User $submitter)
    {
        $this->submitter = $submitter;
        return $this;
    }

    /**
     * @return \User\Entity\User
     */
    public function getSubmitter()
    {
        return $this->submitter;
    }

}