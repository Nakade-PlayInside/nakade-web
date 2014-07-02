<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a Match
 *
 * @ORM\Entity
 * @ORM\Table(name="matches")
 */
class Match extends MatchModel
{

    /**
     * @return mixed
     */
  public function getTime()
  {
      return $this->getDate()->format('H:i:s');
  }

    /**
     * @return bool
     */
    public function hasResult()
    {
        $result = $this->getResult();
        return !is_null($result);
    }


    /**
     * @return string
     */
    public function getMatchInfo()
    {
        return sprintf("%s - %s",
            $this->getBlack()->getShortName(),
            $this->getWhite()->getShortName()
        );
    }

    /**
     * todo: remove?
     * for form data
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {
        return;
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