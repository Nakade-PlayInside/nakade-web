<?php
namespace Authentication\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a Credential
 *
 * @ORM\Entity
 * @ORM\Table(name="credentials")
 * @property int $_id
 * @property string $_username
 * @property string $_password
 * @property boolean $_active
 * @property boolean $_verified
 * @property DateTime   $_lastLogin
 * @property DateTime   $_firstLogin
 */
class User
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
  protected $id;

  
  /**
   * UserName
   *
   * @ORM\Column(name="username", type="string")
   * @var string
   * @access protected
   */
  protected $username;
  
  /**
   * password
   *
   * @ORM\Column(name="password", type="string")
   * @var string
   * @access protected
   */
  protected $password;
  
  /**
   * verified
   *
   * @ORM\Column(name="verified", type="boolean")
   * @var bool
   * @access protected
   */
  protected $verified;
  
  /**
   * active
   *
   * @ORM\Column(name="active", type="boolean")
   * @var bool
   * @access protected
   */
  protected $active;
  
  /**
   * lastLogin
   *
   * @ORM\Column(name="lastLogin", type="datetime")
   * @var DateTime
   * @access protected
   */
  protected $lastLogin;
  
  /**
   * firstLogin
   *
   * @ORM\Column(name="firstLogin", type="datetime")
   * @var DateTime
   * @access protected
   */
  protected $firstLogin;
  

  /**
   * Sets the Identifier
   *
   * @param int $uid
   * @access public
   * @return User
   */
  public function setId($uid)
  {
    $this->id = $uid;
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
    return $this->id;
  }


  /**
   * Sets the Username
   *
   * @param string $firstname
   * @access public
   * @return Credential
   */
  public function setUsername($firstname)
  {
    $this->username = $firstname;
    return $this;
  }

  /**
   * Returns the username
   *
   * @access public
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }
  
  /**
   * Sets the password
   *
   * @param string $lastname
   * @access public
   * @return Credential
   */
  public function setPassword($lastname)
  {
    $this->password = $lastname;
    return $this;
  }

  /**
   * Returns the password
   *
   * @access public
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }
  
  /**
   * Sets the Date of the last Login
   *
   * @param string $datetime
   * @access public
   * @return Credential
   */
  public function setLastLogin($datetime)
  {
    $this->lastLogin = $datetime;
    return $this;
  }

  /**
   * Returns the Date of the last Login
   *
   * @access public
   * @return DateTime
   */
  public function getLastLogin()
  {
    return $this->lastLogin;
  }
  
  /**
   * Sets the Date of the first Login
   *
   * @param string $datetime
   * @access public
   * @return Credential
   */
  public function setFirstLogin($datetime)
  {
    $this->firstLogin = $datetime;
    return $this;
  }

  /**
   * Returns the Date of the first Login
   *
   * @access public
   * @return DateTime
   */
  public function getFirstLogin()
  {
    return $this->firstLogin;
  }
  
  /**
   * Sets active flag
   *
   * @param bool $active
   * @access public
   * @return Credential
   */
  public function setActive($active)
  {
    $this->active = $active;
    return $this;
  }

  /**
   * Returns the flag active
   *
   * @access public
   * @return bool
   */
  public function isActive()
  {
    
      return $this->active;
  }
  
  
  /**
   * Sets verified flag
   *
   * @param bool $verified
   * @access public
   * @return Credential
   */
  public function setVerified($verified)
  {
    $this->verified = $verified;
    return $this;
  }

  /**
   * Returns the flag verified
   *
   * @access public
   * @return bool
   */
  public function isVerified()
  {
    
      return $this->verified==1? TRUE:FALSE;
  }
}