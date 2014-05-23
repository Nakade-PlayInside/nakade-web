<?php

namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * * Class Result
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="result")
 */
class Result
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
   * @ORM\Column(name="name", type="string", nullable=false)
   */
   private $name;

  /**
   * @ORM\Column(name="description", type="string")
   */
   private $description;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}