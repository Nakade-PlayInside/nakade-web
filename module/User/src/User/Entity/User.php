<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a User
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @property int $uid
 * @property string $_vorname
 * @property string $_nachname
 * @property string $_nick
 * @property string $_title
 * @property char   $_sex
 * @property date   $_birthday
 * @property bool   $_anonym
 */
class User
{
  
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="uid", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var integer
   * @access protected
   */
  protected $_id;

  /**
   * Title
   *
   * @ORM\Column(name="titel", type="string")
   * @var string
   * @access protected
   */
  protected $_title;

  /**
   * First Name
   *
   * @ORM\Column(name="vorname", type="string")
   * @var string
   * @access protected
   */
  protected $_firstname;
  
  /**
   * Family Name
   *
   * @ORM\Column(name="nachname", type="string")
   * @var string
   * @access protected
   */
  protected $_lastname;
  
  /**
   * Nick Name
   *
   * @ORM\Column(name="nick", type="string")
   * @var string
   * @access protected
   */
  protected $_nickname;
  
  /**
   * Sex
   *
   * @ORM\Column(name="sex", type="string")
   * @var char
   * @access protected
   */
  protected $_sex;
  
  /**
   * Birthday
   *
   * @ORM\Column(name="geburtsdatum", type="date")
   * @var DateTime
   * @access protected
   */
  protected $_birthday;
  
  /**
   * if flag is set the nickname is shown
   *
   * @ORM\Column(name="anonym", type="boolean")
   * @var bool
   * @access protected
   */
  protected $_anonym;

  /**
   * Sets the Identifier
   *
   * @param int $uid
   * @access public
   * @return User
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
   * Sets the Title
   *
   * @param string $title
   * @access public
   * @return User
   */
  public function setTitle($title)
  {
    $this->_title = $title;
    return $this;
  }

  /**
   * Returns the Title
   *
   * @access public
   * @return string
   */
  public function getTitle()
  {
    return $this->_title;
  }

  /**
   * Sets the First Name
   *
   * @param string $firstname
   * @access public
   * @return User
   */
  public function setFirstName($firstname)
  {
    $this->_firstname = $firstname;
    return $this;
  }

  /**
   * Returns the First Name
   *
   * @access public
   * @return string
   */
  public function getFirstName()
  {
    return $this->_firstname;
  }
  
  /**
   * Sets the Family Name
   *
   * @param string $lastname
   * @access public
   * @return User
   */
  public function setLastName($lastname)
  {
    $this->_lastname = $lastname;
    return $this;
  }

  /**
   * Returns the Last Name
   *
   * @access public
   * @return string
   */
  public function getLastName()
  {
    return $this->_lastname;
  }
  
  /**
   * Sets the Nick Name
   *
   * @param string $nickname
   * @access public
   * @return User
   */
  public function setNickName($nickname)
  {
    $this->_nickname = $nickname;
    return $this;
  }

  /**
   * Returns the Nick Name
   *
   * @access public
   * @return string
   */
  public function getNickName()
  {
    return $this->_nickname;
  }
  
  /**
   * Sets the sex
   *
   * @param char $sex
   * @access public
   * @return User
   */
  public function setSex($sex)
  {
    $this->_sex = $sex;
    return $this;
  }

  /**
   * Returns the sex
   *
   * @access public
   * @return char
   */
  public function getSex()
  {
    return $this->_sex;
  }
  
  /**
   * Sets the birthday
   *
   * @param DateTime $birthday
   * @access public
   * @return User
   */
  public function setBirthday($birthday)
  {
    $this->_birthday = $birthday;
    return $this;
  }

  /**
   * Returns the birthday
   *
   * @access public
   * @return DateTime
   */
  public function getBirthday()
  {
    
      return $this->_birthday;
  }
  
  /**
   * Sets anonymimizer
   *
   * @param bool $anonym
   * @access public
   * @return User
   */
  public function setAnonym($anonym)
  {
    $this->_anonym = $anonym;
    return $this;
  }

  /**
   * Returns the flag anonym
   *
   * @access public
   * @return bool
   */
  public function isAnonym()
  {
    
      return $this->_anonym;
  }
  
}