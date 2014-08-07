<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;
use Season\Entity\Match;
use User\Entity\User;

/**
 * Class ResultReminder
 *
 * @package League\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="resultReminder")
 */
class ResultReminder
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Match", cascade={"persist"})
     * @ORM\JoinColumn(name="match", referencedColumnName="id", nullable=false)
     */
    private $match;

    /**
     * @ORM\Column(name="nextDate", type="datetime")
     */
    private $nextDate;

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
     * @param Match $match
     */
    public function setMatch(Match $match)
    {
        $this->match = $match;
    }

    /**
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param \DateTime $nextDate
     */
    public function setNextDate($nextDate)
    {
        $this->nextDate = $nextDate;
    }

    /**
     * @return \DateTime
     */
    public function getNextDate()
    {
        return $this->nextDate;
    }



}