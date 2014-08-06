<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Title
 *
 * @package Title\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="tieBreaker")
 */
class TieBreaker
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
   * @ORM\Column(name="name", type="string", length=20, unique=true, nullable=false)
   */
  private $name;

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
     * @return string
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

}