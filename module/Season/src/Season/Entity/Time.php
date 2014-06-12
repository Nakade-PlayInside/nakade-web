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
   private $baseTime=60;

   /**
    * @ORM\Column(name="additionalTime", type="integer")
    */
   private $additionalTime=10;

   /**
    * @ORM\Column(name="moves", type="integer")
    */
   private $moves=30;

    /**
     * @ORM\Column(name="period", type="integer")
     */
    private $period=1;

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
     * @param Byoyomi $byoyomi
     */
    public function setByoyomi(Byoyomi $byoyomi)
    {
        $this->byoyomi = $byoyomi;
    }

    /**
     * @return Byoyomi
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

    /**
     * @param int $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return bool
     */
    public function hasAdditionalTime()
    {
        return !empty($this->additionalTime);
    }

    /**
     * @return string
     */
    public function getMatchInfo()
    {
        $info = $this->getBaseTime().' min.';

        if ($this->hasAdditionalTime()) {
            $extra = sprintf(', %s %s/%s',
                $this->getByoyomi()->getName(),
                $this->getMoves(),
                $this->getAdditionalTime()
            );
            $info .= $extra;
        }
        return $info;
    }

    /**
     * for form data
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {

        if (isset($data['period'])) {
            $this->period = intval($data['period']);
        }
        if (isset($data['moves'])) {
            $this->moves = intval($data['moves']);
        }
        if (isset($data['additionalTime'])) {
            $this->additionalTime = intval($data['additionalTime']);
        }
        if (isset($data['baseTime'])) {
            $this->baseTime = intval($data['baseTime']);
        }
    }

    /**
     * needed for form data
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}