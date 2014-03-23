<?php

namespace Message\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mailMessages")
 */
class Message
{
   /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

   /**
    * @ORM\Column(name="threadId", type="integer", nullable=true)
    */
  private $threadId;

    /**
     * @param mixed $threadId
     */
    public function setThreadId($threadId)
    {
        $this->threadId = $threadId;
    }

    /**
     * @return mixed
     */
    public function getThreadId()
    {
        return $this->threadId;
    }

    /**
   * @ORM\Column(name="subject", type="string", nullable=false)
   */
  private $subject;

  /**
   * @ORM\Column(name="message", type="text", nullable=false)
   */
  private $message;

  /**
   * @var \User\Entity\User
   *
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="sender", referencedColumnName="uid", nullable=false)
   *
   */
  private $sender;

  /**
   * @var \User\Entity\User
   *
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="receiver", referencedColumnName="uid", nullable=false)
   *
   */
  private $receiver;

  /**
   * @ORM\Column(name="sendDate", type="datetime", nullable=false)
   */
  private $sendDate;

  /**
   * @ORM\Column(name="readDate", type="datetime", nullable=true)
   */
  private $readDate;

  /**
  * @ORM\Column(name="isHidden", type="boolean")
  */
  private $hidden=0;

  /**
  * @ORM\Column(name="isNew", type="boolean")
  */
  private $new=1;


    public function setNew($isNew)
    {
        $this->new = $isNew;
    }

    public function isNew()
    {
        return $this->new;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function getSendDate()
    {
        return $this->sendDate;
    }

    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
        return $this;
    }

    public function getReadDate()
    {
        return $this->readDate;
    }

    public function setReadDate($readDate)
    {
        $this->readDate = $readDate;
        return $this;
    }

    /**
     * @param boolean $hidden
     *
     * @return $this
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHidden()
    {
        return $this->hidden;
    }




}