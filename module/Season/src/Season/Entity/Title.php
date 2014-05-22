<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Title
 *
 * @package Title\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="seasonTitle")
 */
class Title
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
   * @ORM\Column(name="name", type="string")
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