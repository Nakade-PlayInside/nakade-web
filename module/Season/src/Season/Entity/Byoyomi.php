<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Byoyomi
 *
 * @package Byoyomi\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="byoyomi")
 */
class Byoyomi
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
   * @ORM\Column(name="penalty", type="string", length=45, nullable=false)
   */
   private $penalty;

   /**
    * @ORM\Column(name="description", type="text")
    */
   private $description;

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

    /**
     * @param string $penalty
     */
    public function setPenalty($penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * @return string
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

}