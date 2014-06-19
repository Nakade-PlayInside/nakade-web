<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;

/**
 * Class Result
 *
 * @package League\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="new_result")
 */
class Result
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\League\Entity\ResultType", cascade={"persist"})
     * @ORM\JoinColumn(name="resultType", referencedColumnName="id", nullable=false)
     */
    private $resultType;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="winner", referencedColumnName="id")
     */
    private $winner;


    /**
     * @ORM\Column(name="points", type="float")
     */
    private $points;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="enteredBy", referencedColumnName="id", nullable=false)
     */
    private $enteredBy;

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
     * @param User $enteredBy
     */
    public function setEnteredBy(User $enteredBy)
    {
        $this->enteredBy = $enteredBy;
    }

    /**
     * @return User
     */
    public function getEnteredBy()
    {
        return $this->enteredBy;
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
     * @param float $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return float
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param ResultType $resultType
     */
    public function setResultType(ResultType $resultType)
    {
        $this->resultType = $resultType;
    }

    /**
     * @return ResultType
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * @param User $winner
     */
    public function setWinner(User $winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return User
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @return bool
     */
    public function hasWinner()
    {
        return !is_null($this->winner);
    }

}