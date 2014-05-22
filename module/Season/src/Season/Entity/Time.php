<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Title
 *
 * @package Title\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="time")
 */
class Time
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
    * @ORM\ManyToOne(targetEntity="\Season\Entity\Byoyomi", cascade={"persist"})
    * @ORM\JoinColumn(name="byoyomi", referencedColumnName="id", nullable=false)
    */
   private $byoyomi;

  /**
   * @ORM\Column(name="baseTime", type="integer", nullable=false)
   */
   private $baseTime;

   /**
    * @ORM\Column(name="additionalTime", type="integer", nullable=false)
    */
   private $additionalTime;

   /**
    * @ORM\Column(name="moves", type="integer", nullable=false)
    */
   private $moves;

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
     * @param int $additionalTime
     */
    public function setAdditionalTime($additionalTime)
    {
        $this->additionalTime = $additionalTime;
    }

    /**
     * @return int
     */
    public function getAdditionalTime()
    {
        return $this->additionalTime;
    }

    /**
     * @param int $baseTime
     */
    public function setBaseTime($baseTime)
    {
        $this->baseTime = $baseTime;
    }

    /**
     * @return int
     */
    public function getBaseTime()
    {
        return $this->baseTime;
    }

    /**
     * @param mixed $byoyomi
     */
    public function setByoyomi($byoyomi)
    {
        $this->byoyomi = $byoyomi;
    }

    /**
     * @return mixed
     */
    public function getByoyomi()
    {
        return $this->byoyomi;
    }

    /**
     * @param int $moves
     */
    public function setMoves($moves)
    {
        $this->moves = $moves;
    }

    /**
     * @return int
     */
    public function getMoves()
    {
        return $this->moves;
    }

}