<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;

/**
 * Class Association
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="association")
 */
class Association
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
     * @ORM\Column(name="name", type="string", length=45, unique=true, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\EventType", cascade={"persist"})
     * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\SeasonDates", cascade={"persist"})
     * @ORM\JoinColumn(name="seasonDates", referencedColumnName="id", nullable=false)
     */
    private $seasonDates;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="owner", referencedColumnName="uid", nullable=false)
     */
    private $owner;


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
     * @param SeasonDates $seasonDates
     */
    public function setSeasonDates(SeasonDates $seasonDates)
    {
        $this->seasonDates = $seasonDates;
    }

    /**
     * @return SeasonDates
     */
    public function getSeasonDates()
    {
        return $this->seasonDates;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param EventType $type
     */
    public function setType(EventType $type)
    {
        $this->type = $type;
    }

    /**
     * @return EventType
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param User $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }


}