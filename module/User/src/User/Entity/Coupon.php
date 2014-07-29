<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * invitation to register
 *
 * @ORM\Entity
 * @ORM\Table(name="coupon")
 *
 */
class Coupon
{
    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=120, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="code", type="string", length=32, unique=true, nullable=false)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="uid", nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @ORM\Column(name="expiryDate", type="datetime", nullable=false)
     */
    private $expiryDate;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="usedBy", referencedColumnName="uid")
     */
    private $usedBy;

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param \DateTime $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
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
     * @param User $usedBy
     */
    public function setUsedBy(User $usedBy)
    {
        $this->usedBy = $usedBy;
    }

    /**
     * @return User
     */
    public function getUsedBy()
    {
        return $this->usedBy;
    }



}