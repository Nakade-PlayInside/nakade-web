<?php
namespace Moderator\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SupportStage
 *
 * @package Moderator\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="supportStage")
 */
class SupportStage implements StageInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="stage", type="string", length=10, unique=true, nullable=false)
     */
    private $stage;

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
     * @param string $stage
     */
    public function setStage($stage)
    {
        $this->stage = $stage;
    }

    /**
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @return bool
     */
    public function isSolved()
    {
        return ($this->getId() == self::STAGE_DONE || $this->getId() == self::STAGE_CANCELED);
    }

    /**
     * @return bool
     */
    public function isOngoing()
    {
        return ($this->getId() == self::STAGE_IN_PROCESS ||
            $this->getId() == self::STAGE_REOPENED ||
            $this->getId() == self::STAGE_WAITING);
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->getId() == self::STAGE_NEW;
    }

}