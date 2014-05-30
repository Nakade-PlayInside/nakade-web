<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatchDay
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="matchDay")
 */
class MatchDay
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
   * @ORM\Column(name="time", type="integer", nullable=false)
   */
    private $time=1;

  /**
   * @ORM\Column(name="span", type="integer", nullable=false)
   */
    private $span;

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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $span
     */
    public function setSpan($span)
    {
        $this->span = $span;
    }

    /**
     * @return mixed
     */
    public function getSpan()
    {
        return $this->span;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }



}