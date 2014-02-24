<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueParticipants")
 * @property int $lid
 * @property int $id
 * @property int $sid
 * @property int $uid
 */
class Player
{

  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="pid", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var integer
   * @access protected
   */
  protected $id;


  /**
   * League Identifier: Foreign Key
   *
   * @ORM\Column(name="lid", type="integer")
   * @var integer
   * @access protected
   */
  protected $lid;

  /**
   * Season Id: Foreign Key
   *
   * @ORM\Column(name="sid", type="integer")
   * @var int
   * @access protected
   */
  protected $sid;

  /**
   * User Id: Foreign Key
   *
   * @ORM\Column(name="uid", type="integer")
   * @var int
   */
  protected $uid;


  /**
   * Sets the Identifier
   *
   * @param int $pid
   *
   * @return Pairings
   */
  public function setId($pid)
  {
    $this->id = $pid;
    return $this;
  }

  /**
   * Returns the Identifier
   *
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Sets the LeagueId
   *
   * @param int $lid
   *
   * @return League
   */
  public function setLid($lid)
  {
    $this->lid = $lid;
    return $this;
  }

  /**
   * Returns the LeagueId
   *
   * @access public
   * @return int
   */
  public function getLid()
  {
    return $this->lid;
  }


  /**
   * Sets the SeasonId
   *
   * @param int $sid
   *
   * @return Season
   */
  public function setSid($sid)
  {
    $this->sid = $sid;
    return $this;
  }

  /**
   * Returns the seasonId
   *
   * @access public
   * @return int
   */
  public function getSid()
  {
    return $this->sid;
  }

  /**
   * Sets UserId
   *
   * @param int $uid
   *
   * @return User
   */
  public function setUid($uid)
  {
    $this->uid = $uid;
    return $this;
  }

  /**
   * Returns uid
   *
   * @access public
   * @return int
   */
  public function getUid()
  {
    return $this->uid;
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
   * Convert the object to an array.
   *
   * @return array
   */
   public function getArrayCopy()
   {
        return get_object_vars($this);
   }


}