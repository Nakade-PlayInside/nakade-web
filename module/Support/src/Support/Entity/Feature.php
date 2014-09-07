<?php
namespace Support\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;


/**
 * Class Feature
 *
 * @package Support\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="feature")
 */
class Feature
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=45, nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="creator", referencedColumnName="uid", nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(name="createDate", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @ORM\Column(name="startDate", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(name="doneDate", type="datetime", nullable=true)
     */
    private $doneDate;

    /**
     * @ORM\ManyToOne(targetEntity="\Support\Entity\SupportStage", cascade={"persist"})
     * @ORM\JoinColumn(name="stage", referencedColumnName="id", nullable=false)
     */
    private $stage;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }



}