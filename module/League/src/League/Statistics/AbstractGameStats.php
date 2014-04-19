<?php
namespace League\Statistics;

/**
 * Abstract class for game statistics.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractGameStats
{

    protected $allMatches;
    protected $name;
    protected $description;


    /**
     * setter for entities of matches
     *
     * @param array $allMatches
     *
     * @return $this
     */
    public function setMatches(array $allMatches)
    {

        $this->allMatches = $allMatches;
        return $this;
    }

    /**
     * getter for matches
     * @return array
     */
    public function getMatches()
    {

        return $this->allMatches;
    }

    /**
     * Name of the Tiebreaker
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Description of the Tiebreaker
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

}
