<?php
namespace Season\Entity;


use User\Entity\User;
use League\Entity\Result;
use Doctrine\ORM\Mapping as ORM;

/**
* Entity Class representing a Match
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
     * @ORM\Column(name="matchDay", type="integer")
     */
    private $matchDay;

   /**
   * @ORM\Column(name="date", type="datetime")
   */
    private $date;

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
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
     * @param mixed $matchDay
     */
    public function setMatchDay($matchDay)
    {
        $this->matchDay = $matchDay;
    }

    /**
     * @return mixed
     */
    public function getMatchDay()
    {
        return $this->matchDay;
    }

}