<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;
use Season\Entity\Match;

/**
 * Class MatchReminder
 *
 * @package League\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="appointmentReminder")
 */
class AppointmentReminder
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Season\Entity\Match", cascade={"persist"})
     * @ORM\JoinColumn(name="myMatch", referencedColumnName="id", unique=true, nullable=false)
     */
    private $match;

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

}