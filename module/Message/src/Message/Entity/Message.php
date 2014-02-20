<?php

namespace Message\Entity;

//use League\Entity\Season;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 * @property int $id
 * @property string $subject
 * @property text $message
 * @property User $senderId 
 * @property User $receiverId 
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
   * @ORM\Column(name="subject", type="string")
   */
  private $subject;
  
  /**
   * @ORM\Column(name="message", type="text")
   */
  private $message;
  
  /**
   * @var User\Entity\User
   *
   * @ORM\OneToOne(targetEntity="User\Entity\User")
   * @ORM\JoinColumns({
   * @ORM\JoinColumn(name="senderId", referencedColumnName="uid")
   * })
   */
  private $sender;
  
  /**
   * @var User\Entity\User
   *
   * @ORM\ManyToOne(targetEntity="User\Entity\User")
   * @ORM\JoinColumns({
   * @ORM\JoinColumn(name="receiverId", referencedColumnName="uid")
   * })
   */
  private $receiver;
  
  /**
   * @ORM\Column(name="sendDate", type="datetime")
   */
  private $sendDate;
  
  /**
   * @ORM\Column(name="readDate", type="datetime")
   */
  private $readDate;
    
  /**
   * @ORM\Column(name="box", type="string")
   */
  private $mailbox;
  
    public function getId() 
    {
        $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getSubject() 
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getMessage() 
    {
        return $this->message;
    }

    public function setMessage($message) 
    {
        $this->message = $message;
    }

    public function getReceiver() 
    {
        return $this->receiver;
    }

    public function setReceiver($receiver) 
    {
        $this->receiver = $receiver;
    }

    public function getSender() 
    {
        return $this->sender;
    }

    public function setSender($sender) 
    {
        $this->sender = $sender;
    }

    public function getSendDate() 
    {
        return $this->sendDate;
    }

    public function setSendDate($sendDate) 
    {
        $this->sendDate = $sendDate;
    }

    public function getReadDate() 
    {
        return $this->readDate;
    }

    public function setReadDate($readDate) 
    {
        $this->readDate = $readDate;
    }
    
    public function getMailbox() 
    {
        return $this->mailbox;
    }

    public function setMailbox($mailbox) 
    {
        $this->mailbox = $mailbox;
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