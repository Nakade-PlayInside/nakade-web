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
     * @ORM\ManyToOne(targetEntity="\League\Entity\Match", cascade={"persist"})
     * @ORM\JoinColumn(name="pairing", referencedColumnName="id", nullable=false)
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
   * @ORM\Column(name="submitDate", type="datetime", nullable=false)
   */
  private $submitDate;

  /**
   * @ORM\Column(name="oldDate", type="datetime", nullable=false)
   */
  private $oldDate;

  /**
   * @ORM\Column(name="newDate", type="datetime", nullable=false)
   */
  private $newDate;

 /**
  * @ORM\Column(name="isConfirmed", type="boolean", nullable=true)
  */
  private $isConfirmed=0;

  /**
   * @ORM\Column(name="isRejected", type="boolean", nullable=true)
   */
  private $isRejected=0;

  /**
   * @ORM\Column(name="rejectReason", type="text", nullable=true)
   */
  private $rejectReason;

    /**
     * @param \DateTime $newDate
     *
     * @return $this
     */
    public function setNewDate($newDate)
    {
        $this->newDate = $newDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNewDate()
    {
        return $this->newDate;
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

    /**
     * @param bool $isConfirmed
     *
     * @return $this
     */
    public function setIsConfirmed($isConfirmed)
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
     * @param boolean $isRejected
     *
     * @return $this
     */
    public function setIsRejected($isRejected)
    {
        $this->isRejected = $isRejected;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRejected()
    {
        return $this->isRejected;
    }

    /**
     * @param string $rejectReason
     *
     * @return $this
     */
    public function setRejectReason($rejectReason)
    {
        $this->rejectReason = $rejectReason;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRejectReason()
    {
        return $this->rejectReason;
    }

    /**
     * for form data
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {

        if (isset($data['date']) &&  isset($data['time'])) {
            $this->newDate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date'] . ' ' . $data['time']);
        }
        if (isset($data['reason'])) {
            $this->rejectReason = $data['reason'];
        }
    }

    /**
     * needed for form data
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}