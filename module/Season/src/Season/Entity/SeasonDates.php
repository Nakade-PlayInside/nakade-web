<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeasonDates
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="seasonDates")
 */
class SeasonDates
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
     * @ORM\Column(name="day", type="integer", nullable=false)
     */
    private $day;

  /**
   * @ORM\Column(name="time", type="time", nullable=false)
   */
    private $time;

  /**
   * @ORM\Column(name="cycle", type="integer", nullable=false)
   */
    private $cycle;

    /**
     * @param int $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
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
     * @param int $cycle
     */
    public function setCycle($cycle)
    {
        $this->cycle = $cycle;
    }

    /**
     * @return int
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }


    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        if (isset($data['cycle'])) {
            $this->cycle = intval($data['cycle']);
        }
        if (isset($data['day'])) {
            $this->day = intval($data['day']);
        }
        if (isset($data['time'])) {
            $this->time = $data['time'];
        }

    }

}