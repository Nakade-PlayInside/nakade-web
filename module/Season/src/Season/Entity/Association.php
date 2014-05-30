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
   * @ORM\ManyToOne(targetEntity="\Season\Entity\MatchDay", cascade={"persist"})
   * @ORM\JoinColumn(name="matchDay", referencedColumnName="id", nullable=false)
   */
   private $matchDay;

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
   * @param MatchDay $matchDay
   */
  public function setMatchDay(MatchDay $matchDay)
  {
      $this->matchDay = $matchDay;
  }

  /**
   * @return MatchDay
   */
  public function getMatchDay()
  {
     return $this->matchDay;
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