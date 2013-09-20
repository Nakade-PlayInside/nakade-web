<?php

namespace Message\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 * @property int $id
 * @property string $subject
 * @property text $message
 * @property User $sender
 * @property User $receiver 
 * @property DateTime $sendDate 
 * @property DateTime $readDate 
 * @property string $box 
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
   * @ORM\Column(name="subject", type="string", nullable=false)
   */
  private $subject;
  
  /**
   * @ORM\Column(name="message", type="text", nullable=false)
   */
  private $message;
  
  /**
   * @var User\Entity\User
   *
   * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
   * @ORM\JoinColumn(name="sender", referencedColumnName="uid", nullable=false)
   * 
   */
  private $sender;
  
  /**
   * @var User\Entity\User
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
   * @ORM\Column(name="box", type="string", nullable=false)
   */
  private $mailbox="maibox";
  
 
  
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
    
    public function getMailbox() 
    {
        return $this->mailbox;
    }

    public function setMailbox($mailbox) 
    {
        $this->mailbox = $mailbox;
        return $this;
    }
    
    
    
  /**
   * populating data as an array.
   * key of the array is getter methods name. 
   * 
   * @param array $data
   */
  public function populate($data) 
  {
       foreach($data as $key => $value) {
           
           $key = str_replace('_', '',$key);
           $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
       }
       
  }
  
  /**
   * usage for creating a NEW league. Provide all neccessary values 
   * in an array. 
   *    
   * @param array $data
   */
  public function exchangeArray($data)
  {
        $this->populate($data);
        
   }
   
   /**
    * Convert the object to an array.
    *
    * @return array
    */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
 
}