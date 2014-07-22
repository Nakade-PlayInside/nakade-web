<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\MappedSuperclass
 */
class UserModel
{
  /**
   * Primary Identifier
   *
   * @ORM\Id
   * @ORM\Column(name="uid", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="titel", type="string")
   */
  protected $title;

  /**
   * @ORM\Column(name="vorname", type="string")
    */
  protected $firstName;

  /**
   * @ORM\Column(name="nachname", type="string")
   */
  protected $lastName;

 /**
   * @ORM\Column(name="nick", type="string")
   */
  protected $nickname;

  /**
   * @ORM\Column(name="sex", type="string")
   */
  protected $sex;

  /**
   * Birthday
   *
   * @ORM\Column(name="geburtsdatum", type="date")
   * @var \DateTime
   */
  protected $birthday;

  /**
   * if flag is set the nickname is shown
   *
   * @ORM\Column(name="anonym", type="boolean")
   * @var bool
   */
  protected $anonymous;

  /**
   * username
   *
   * @ORM\Column(name="username", type="string")
   * @var string
   */
  protected $username;

  /**
   * password
   *
   * @ORM\Column(name="password", type="string")
   * @var string
   */
  protected $password;

   /**
    * kgs username
    *
    * @ORM\Column(name="kgs", type="string")
    * @var string
    */
  protected $kgs;

  /**
   * email
   *
   * @ORM\Column(name="email", type="string")
   * @var string
   */
  protected $email;

  /**
   * verifyString
   *
   * @ORM\Column(name="verifyString", type="string")
   * @var string
   */
  protected $verifyString;

  /**
   * @ORM\Column(name="verified", type="boolean")
   */
  protected $verified;

  /**
   * @ORM\Column(name="active", type="boolean")
   */
  protected $active;

  /**
   * @ORM\Column(name="pwdChange", type="datetime")
   */
  protected $pwdChange;

  /**
   * @ORM\Column(name="edit", type="datetime")
   */
  protected $edit;

  /**
   * @ORM\Column(name="lastLogin", type="datetime")
   */
  protected $lastLogin;

  /**
   * @ORM\Column(name="firstLogin", type="datetime")
   */
  protected $firstLogin;

  /**
   * @ORM\Column(name="created", type="datetime")
   */
  protected $created;

  /**
   * @ORM\Column(name="due", type="datetime")
    */
  protected $due;

  /**
   * @ORM\Column(name="role", type="string")
   */
  protected $role;

 /**
 * @ORM\Column(name="language", type="string")
 */
 protected $language;


  /**
   * generated password is set for mail methods
   *
   * @var string
   */
  protected $generated;

  /**
   * Sets the Identifier
   *
   * @param int $uid
   *
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
   *
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Sets the Title.
   * If empty string, null is set
   *
   * @param string $title
   *
   * @return User
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param string $firstName
   *
   * @return User
   */
  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;
    return $this;
  }

  /**
   * @return string
   */
  public function getFirstName()
  {
    return $this->firstName;
  }

  /**
   * @param string $lastName
   *
   * @return User
   */
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;
    return $this;
  }

  /**
   * @return string
   */
  public function getLastName()
  {
    return $this->lastName;
  }

  /**
   * @param string $nickname
   *
   * @return User
   */
  public function setNickname($nickname)
  {
    $this->nickname = $nickname;
    return $this;
  }

  /**
   * @return string
   */
  public function getNickname()
  {
    return $this->nickname;
  }

  /**
   * @param string $name
   *
   * @return User
   */
  public function setUsername($name)
  {
    $this->username = $name;
    return $this;
  }

  /**
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @param string $password
   *
   * @return user
   */
  public function setPassword($password)
  {
    $this->password = $password;
    return $this;
  }

  /**
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }

    /**
    * @param string $name
     *
    * @return User
    */
    public function setKgs($name)
    {
    $this->kgs = $name;
    return $this;
    }

    /**
    * @return string
    */
    public function getKgs()
    {
    return $this->kgs;
    }

  /**
   * @param string $password
   *
   * @return user
   */
  public function setGenerated($password)
  {
    $this->generated = $password;
    return $this;
  }

  /**
   * Returns the generated password
   *
   * @return string
   */
  public function getGenerated()
  {
    return $this->generated;
  }

  /**
   * Sets the email
   *
   * @param string $email
   *
   * @return user
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * Returns the email
   *
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Sets the sex
   *
   * @param string $sex
   *
   * @return User
   */
  public function setSex($sex)
  {
    $this->sex = $sex;
    return $this;
  }

  /**
   * Returns the sex
   *
   * @return string
   */
  public function getSex()
  {
    return $this->sex;
  }

  /**
   * @param \DateTime $datetime
   *
   * @return User
   */
  public function setBirthday($datetime)
  {
    $this->birthday = $datetime;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getBirthday()
  {
      return $this->birthday;
  }

  /**
   * @param bool $isAnonymous
   *
   * @return User
   */
  public function setAnonymous($isAnonymous)
  {
    $this->anonymous = $isAnonymous;
    return $this;
  }


  /**
   * Returns the flag anonymous
   *
   * @return bool
   */
  public function isAnonymous()
  {
      return $this->anonymous;
  }

  /**
   * @param string $verify
   *
   * @return user
   */
  public function setVerifyString($verify)
  {
    $this->verifyString = $verify;
    return $this;
  }

  /**
   * @return string
   */
  public function getVerifyString()
  {
    return $this->verifyString;
  }

  /**
   * @param \DateTime $datetime
   *
   * @return user
   */
  public function setCreated($datetime)
  {
      $this->created = $datetime;
      return $this;
  }

  /**
   * @return \DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * @param \DateTime $datetime
   *
   * @return user
   */
  public function setEdit($datetime)
  {
      $this->edit = $datetime;
      return $this;
  }

  /**
   * Returns the Date  of last time editing
   *
   * @return \DateTime
   */
  public function getEdit()
  {
    return $this->edit;
  }

  /**
   * Sets the Date of last time pwd change
   *
   * @param \DateTime $datetime
   *
   * @return user
   */
  public function setPwdChange($datetime)
  {
      $this->pwdChange = $datetime;
      return $this;
  }

  /**
   * Returns the Date  of last time pwd change
   *
   * @return \DateTime
   */
  public function getPwdChange()
  {
    return $this->pwdChange;
  }

  /**
   * @param string $role
   *
   * @return user
   */
  public function setRole($role)
  {

    $this->role = $role;
    return $this;
  }

  /**
   * @return string
   */
  public function getRole()
  {
    return $this->role;
  }

  /**
   * @param \DateTime $datetime
   *
   * @return user
   */
  public function setDue($datetime)
  {

    $this->due = $datetime;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getDue()
  {
    return $this->due;
  }


  /**
   * @param \DateTime $datetime
   *
   * @return user
   */
  public function setLastLogin($datetime)
  {

      $this->lastLogin = $datetime;
      return $this;
  }

  /**
   * @return \DateTime
   */
  public function getLastLogin()
  {
    return $this->lastLogin;
  }

  /**
   * @param \DateTime $datetime
   *
   * @return user
   */
  public function setFirstLogin($datetime)
  {
      $this->firstLogin = $datetime;
      return $this;
  }

  /**
   * @return \DateTime
   */
  public function getFirstLogin()
  {
    return $this->firstLogin;
  }

  /**
   * @param bool $active
   *
   * @return user
   */
  public function setActive($active)
  {
    $this->active = $active;
    return $this;
  }

  /**
   * @return bool
   */
  public function isActive()
  {

      return $this->active;
  }

  /**
   * @param bool $verified
   *
   * @return user
   */
  public function setVerified($verified)
  {
    $this->verified = $verified;
    return $this;
  }

  /**
   * @return bool
   */
  public function isVerified()
  {
      return $this->verified;
  }

  /**
  * @param string $language
  */
  public function setLanguage($language)
  {
     $this->language = $language;
  }

  /**
  * @return string
  */
  public function getLanguage()
  {
     return $this->language;
  }

}