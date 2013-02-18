<?php
namespace League\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a User
 *
 * @ORM\Entity
 * @ORM\Table(name="leagueResult")
 * @property int $_id
 * @property string $_result
 */
class Result
{
  
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var integer
   * @access protected
   */
  protected $_id;

  /**
   * Result
   *
   * @ORM\Column(name="result", type="string")
   * @var string
   * @access protected
   */
  protected $_result;

  
  /**
   * Sets the Identifier
   *
   * @param int $uid
   * @access public
   * @return Result
   */
  public function setId($uid)
  {
    $this->_id = $uid;
    return $this;
  }

  /**
   * Returns the Identifier
   *
   * @access public
   * @return int
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * Sets the Result
   *
   * @param string $result
   * @access public
   * @return Result
   */
  public function setResult($result)
  {
    $this->_result = $result;
    return $this;
  }

  /**
   * Returns the Result
   *
   * @access public
   * @return string
   */
  public function getResult()
  {
    return $this->_result;
  }

  
}