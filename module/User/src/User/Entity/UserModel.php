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
   * @var integer
   */
  protected $id;

  /**
   * Title
   *
   * @ORM\Column(name="titel", type="string")
   * @var string
   */
  protected $title;

  /**
   * First Name
   *
   * @ORM\Column(name="vorname", type="string")
   * @var string
   */
  protected $firstname;

  /**
   * Family Name
   *
   * @ORM\Column(name="nachname", type="string")
   * @var string
   */
  protected $lastname;

 /**
   * Nick Name
   *
   * @ORM\Column(name="nick", type="string")
   * @var string
   */
  protected $nickname;

  /**
   * Sex
   *
   * @ORM\Column(name="sex", type="string")
   * @var char
   */
  protected $sex;

  /**
   * Birthday
   *
   * @ORM\Column(name="geburtsdatum", type="date")
   * @var DateTime
   */
  protected $birthday;

  /**
   * if flag is set the nickname is shown
   *
   * @ORM\Column(name="anonym", type="boolean")
   * @var bool
   */
  protected $anonym;

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
   * verified
   *
   * @ORM\Column(name="verified", type="boolean")
   * @var bool
   */
  protected $verified;

  /**
   * active
   *
   * @ORM\Column(name="active", type="boolean")
   * @var bool
   */
  protected $active;

  /**
   * pwdChange
   *
   * @ORM\Column(name="pwdChange", type="datetime")
   * @var DateTime
   */
  protected $pwdChange;

  /**
   * edit
   *
   * @ORM\Column(name="edit", type="datetime")
   * @var DateTime
   */
  protected $edit;

  /**
   * lastLogin
   *
   * @ORM\Column(name="lastLogin", type="datetime")
   * @var DateTime
   */
  protected $lastLogin;

  /**
   * firstLogin
   *
   * @ORM\Column(name="firstLogin", type="datetime")
   * @var DateTime
   */
  protected $firstLogin;

  /**
   * created
   *
   * @ORM\Column(name="created", type="datetime")
   * @var DateTime
   */
  protected $created;

  /**
   * due date
   *
   * @ORM\Column(name="due", type="datetime")
   * @var DateTime
   */
  protected $due;

  /**
   * user's role
   * @ORM\Column(name="role", type="string")
   *
   * @var string
   */
  protected $role;

 /**
 * user's role
 * @ORM\Column(name="language", type="string")
 *
 * @var string
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
   * Returns the Title
   *
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Sets the First Name
   *
   * @param string $firstname
   *
   * @return User
   */
  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;
    return $this;
  }

  /**
   * Returns the First Name
   *
   * @access public
   *
   * @return string
   */
  public function getFirstname()
  {
    return $this->firstname;
  }

  /**
   * Sets the Family Name
   *
   * @param string $lastname
   *
   * @return User
   */
  public function setLastname($lastname)
  {
    $this->lastname = $lastname;
    return $this;
  }

  /**
   * Returns the Last Name
   *
   * @access public
   *
   * @return string
   */
  public function getLastname()
  {
    return $this->lastname;
  }

  /**
   * Sets the Nick Name.
   * if empty string, null is set
   *
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
   * Returns the Nick Name
   *
   * @access public
   *
   * @return string
   */
  public function getNickname()
  {
    return $this->nickname;
  }

  /**
   * Sets the User Name
   *
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
   * Returns the userName
   *
   * @access public
   *
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
   *
   * @return user
   */
  public function setPassword($lastname)
  {
    $this->password = $lastname;
    return $this;
  }

  /**
   * Returns the password
   *
   *
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }

    /**
    * Sets the Kgs Name
    *
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
    * Returns the Kgs Name
    *
    *
    * @return string
    */
    public function getKgs()
    {
    return $this->kgs;
    }

  /**
   * Sets the generated password
   *
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
   * @param char $sex
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
   * @return char
   */
  public function getSex()
  {
    return $this->sex;
  }

  /**
   * Sets the birthday.
   * Converts to DateTime if string is provided
   *
   * @param mixed DateTime|string $datetime
   *
   * @return User
   */
  public function setBirthday($datetime)
  {
    $this->birthday = $datetime;
    return $this;
  }

  /**
   * Returns the birthday
   *
   * @return DateTime
   */
  public function getBirthday()
  {
      return $this->birthday;
  }

  /**
   * Sets anonymimizer
   *
   * @param bool $anonym
   *
   * @return User
   */
  public function setAnonym($anonym)
  {
    $this->anonym = $anonym;
    return $this;
  }


  /**
   * Returns the flag anonym
   *
   * @return bool
   */
  public function isAnonym()
  {

      return $this->anonym;
  }

  /**
   * Sets the verify string
   *
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
   * Returns the verify string
   *
   * @return string
   */
  public function getVerifyString()
  {
    return $this->verifyString;
  }

  /**
   * Sets the Date of creation
   *
   * @param DateTime $datetime
   *
   * @return user
   */
  public function setCreated($datetime)
  {
      $this->created = $datetime;
      return $this;
  }

  /**
   * Returns the Date  of creation
   *
   * @return DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * Sets the Date of last time editing
   *
   * @param DateTime $datetime
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
   * @return DateTime
   */
  public function getEdit()
  {
    return $this->edit;
  }

  /**
   * Sets the Date of last time pwd change
   *
   * @param DateTime $datetime
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
   * @return DateTime
   */
  public function getPwdChange()
  {
    return $this->pwdChange;
  }

  /**
   * Set user's role.
   *
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
   * get user's role
   *
   * @return string
   */
  public function getRole()
  {
    return $this->role;
  }

  /**
   * Sets the due date.
   *
   * @param DateTime $datetime
   *
   * @return user
   */
  public function setDue($datetime)
  {

    $this->due = $datetime;
    return $this;
  }

  /**
   * Returns the due date
   *
   * @return DateTime
   */
  public function getDue()
  {
    return $this->due;
  }


  /**
   * Sets the Date of the last Login.
   *
   * @param DateTime $datetime
   *
   * @return user
   */
  public function setLastLogin($datetime)
  {

      $this->lastLogin = $datetime;
      return $this;
  }

  /**
   * Returns the Date of the last Login
   *
   * @return DateTime
   */
  public function getLastLogin()
  {
    return $this->lastLogin;
  }

  /**
   * Sets the Date of the first Login.
   *
   * @param DateTime $datetime
   *
   * @return user
   */
  public function setFirstLogin($datetime)
  {
      $this->firstLogin = $datetime;
      return $this;
  }

  /**
   * Returns the Date of the first Login
   *
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
   *
   * @return user
   */
  public function setActive($active)
  {
    $this->active = $active;
    return $this;
  }

  /**
   * Returns the flag active
   *
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
   *
   * @return user
   */
  public function setVerified($verified)
  {
    $this->verified = $verified;
    return $this;
  }

  /**
   * Returns the flag verified
   *
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