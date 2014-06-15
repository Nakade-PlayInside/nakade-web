<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

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
   * @ORM\Column(name="name", type="string")
   */
   private $name;

  /**
   * @ORM\ManyToOne(targetEntity="\Season\Entity\SeasonDates", cascade={"persist"})
   * @ORM\JoinColumn(name="seasonDates", referencedColumnName="id", nullable=false)
   */
   private $seasonDates;

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

}