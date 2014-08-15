<?php
namespace Moderator\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;

/**
 * Class SupportRequest
 *
 * @package Moderator\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="supportMessage")
 */
class SupportMessage
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Moderator\Entity\SupportRequest", inversedBy="messages", cascade={"persist"})
     * @ORM\JoinColumn(name="request", referencedColumnName="id", onDelete="cascade")
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="author", referencedColumnName="uid", nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
     * @param SupportRequest $request
     */
    public function setRequest(SupportRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return SupportRequest
     */
    public function getRequest()
    {
        return $this->request;
    }


}