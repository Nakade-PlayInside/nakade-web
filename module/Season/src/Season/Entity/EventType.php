<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;

/**
 * Class EventType
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="eventType")
 */
class EventType
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


}