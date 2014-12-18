<?php
namespace Stats\Entity;

/**
 * Class Certificate
 *
 * @package Stats\Entity
 */
class Certificate
{
    private $tournamentInfo;
    private $name;
    private $award;

    /**
     * @param string $award
     */
    public function setAward($award)
    {
        $this->award = $award;
    }

    /**
     * @return string
     */
    public function getAward()
    {
        return $this->award;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $tournamentInfo
     */
    public function setTournamentInfo($tournamentInfo)
    {
        $this->tournamentInfo = $tournamentInfo;
    }

    /**
     * @return string
     */
    public function getTournamentInfo()
    {
        return $this->tournamentInfo;
    }

    /**
     * @return bool
     */
    public function hasAward()
    {
        return !empty($this->award);
    }

    /**
     * populating data as an array.
     * key of the array is getter methods name.
     *
     * @param array $data
     */

    public function populate($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @param array $data
     */
    public function exchangeArray($data)
    {
        $this->populate($data);

    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


}