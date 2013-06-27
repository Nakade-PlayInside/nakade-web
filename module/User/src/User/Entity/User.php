<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a User
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * 
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
  protected $id;

  /**
   * Title
   *
   * @ORM\Column(name="titel", type="string")
   * @var string
   * @access protected
   */
  protected $title;

  /**
   * First Name
   *
   * @ORM\Column(name="vorname", type="string")
   * @var string
   * @access protected
   */
  protected $firstname;
  
  /**
   * Family Name
   *
   * @ORM\Column(name="nachname", type="string")
   * @var string
   * @access protected
   */
  protected $lastname;
  
  /**
   * Nick Name
   *
   * @ORM\Column(name="nick", type="string")
   * @var string
   * @access protected
   */
  protected $nickname;
  
  /**
   * Sex
   *
   * @ORM\Column(name="sex", type="string")
   * @var char
   * @access protected
   */
  protected $sex;
  
  /**
   * Birthday
   *
   * @ORM\Column(name="geburtsdatum", type="date")
   * @var DateTime
   * @access protected
   */
  protected $birthday;
  
  /**
   * if flag is set the nickname is shown
   *
   * @ORM\Column(name="anonym", type="boolean")
   * @var bool
   * @access protected
   */
  protected $anonym;

  /**
   * username
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
   * email
   *
   * @ORM\Column(name="email", type="string")
   * @var string
   * @access protected
   */
  protected $email;
  
  /**
   * verifyString
   *
   * @ORM\Column(name="verifyString", type="string")
   * @var string
   * @access protected
   */
  protected $verifyString;
  
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
   * pwdChange
   *
   * @ORM\Column(name="pwdChange", type="datetime")
   * @var DateTime
   * @access protected
   */
  protected $pwdChange;
  
  /**
   * edit
   *
   * @ORM\Column(name="edit", type="datetime")
   * @var DateTime
   * @access protected
   */
  protected $edit;
  
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
   * created
   *
   * @ORM\Column(name="created", type="datetime")
   * @var DateTime
   * @access protected
   */
  protected $created;
  
  
  /**
   * due date
   *
   * @ORM\Column(name="due", type="datetime")
   * @var DateTime
   * @access protected
   */
  protected $due;
  
  /**
   * user's role
   * @ORM\Column(name="role", type="string")
   * 
   * @var string
   * @access protected
   */
  protected $role;
  
  /**
   * generated password is set for mail methods
   *
   * @var string
   * @access protected
   */
  protected $generated;
  
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
   * Sets the Title. 
   * If empty string, null is set
   *
   * @param string $title
   * @access public
   * @return User
   */
  public function setTitle($title)
  {
    $temp = empty($title)? null:$title;
    $this->title = $temp;
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
    return $this->title;
  }

  /**
   * Sets the First Name
   *
   * @param string $firstname
   * @access public
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
   * @access public
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
   * @access public
   * @return User
   */
  public function setNickname($nickname)
  {
    $temp = empty($nickname)? null:$nickname;  
    $this->nickname = $temp;
    return $this;
  }

  /**
   * Returns the Nick Name
   *
   * @access public
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
   * @access public
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
   * @access public
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }
  
  /**
   * Sets the generated password
   *
   * @param string $lastname
   * @access public
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
   * @access public
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
   * @access public
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
   * @access public
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
   * @access public
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
   * @access public
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
   * @access public
   * @return User
   */
  public function setBirthday($datetime)
  {
    $temp = empty($datetime)? null : $datetime; 
    if(is_string($temp)) {
        $temp = new \DateTime($temp); 
    }  
    $this->birthday = $temp;
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
    
      return $this->birthday;
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
    $this->anonym = $anonym;
    return $this;
  }

  /**
   * Returns the flag anonym
   *
   * @access public
   * @return bool
   */
  public function getAnonym()
  {
    
      return $this->anonym;
  }
  
  /**
   * Returns the flag anonym
   *
   * @access public
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
   * @access public
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
   * @access public
   * @return string
   */
  public function getVerifyString()
  {
    return $this->verifyString;
  }
  
  /**
   * Sets the Date of creation
   * 
   * @param  DateTime $datetime
   * @access public
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
   * @access public
   * @return DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }
  
  /**
   * Sets the Date of last time editing
   * 
   * @param  DateTime $datetime
   * @access public
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
   * @access public
   * @return DateTime
   */
  public function getEdit()
  {
    return $this->edit;
  }
  
  /**
   * Sets the Date of last time pwd change
   * 
   * @param  DateTime $datetime
   * @access public
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
   * @access public
   * @return DateTime
   */
  public function getPwdChange()
  {
    return $this->pwdChange;
  }
  
  /**
   * Set user's role.
   *
   * @param  string $role
   * @access public
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
   * @access public
   * @return string
   */
  public function getRole()
  {
    return $this->role;
  }
  
  /**
   * Sets the due date.
   *
   * @param  DateTime $datetime
   * @access public
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
   * @access public
   * @return DateTime
   */
  public function getDue()
  {
    return $this->due;
  }
  
  /**
   * returns true if dueDate is not expired
   * 
   * @return boolean
   */
  public function isDue()
  {
      $expired = $this->getDue();    
      if($expired===null)
          return false;
      
      return $expired > new \DateTime();
  }
  
  /**
   * Sets the Date of the last Login.
   *
   * @param  DateTime $datetime
   * @access public
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
   * @access public
   * @return DateTime
   */
  public function getLastLogin()
  {
    return $this->lastLogin;
  }
  
  /**
   * Sets the Date of the first Login.
   * 
   * @param  DateTime $datetime
   * @access public
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
   * @access public
   * @return bool
   */
  public function isVerified()
  {
    
      return $this->verified;
  }
  
  /**
   * gets nickname if existing and anonymized or 
   * firstname instead
   * @return string
   */
  public function getShortName()
  {
      $nick = $this->getNickname();
      if($this->isAnonym()  && !empty($nick)) 
          return $nick;
      
      $lastname = $this->getLastname();
      return $this->getFirstname() . " " . $lastname[0] . ".";
  }
  
  /**
   * get complete name with nickname if existing
   * @return string
   */
  public function getCompleteName()
  {
      $nick = $this->getNickname();
      if($this->isAnonym()  && !empty($nick)) {
          return sprintf("%s %s '%s' %s",
              $this->getTitle(),
              $this->getFirstname(),
              $nick,
              $this->getLastname()
          );
          
      }    
      
      return $this->getName();
  }
  
  /**
   * get name and title 
   * @return string
   */
  public function getName()
  {
      return $this->getTitle() . " " .  
          $this->getFirstname() . " " . 
          $this->getLastname();
  }
  
  
  /**
   * populating data as an array.
   * key of the array is getter methods name. 
   * 
   * @param array $data
   */
  
  public function populate($data) 
  {
       foreach($data as $key => $value) {
            
           $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
       }
       
  }
  
  /**
   * usage for creating a NEW user. Provide all neccessary values 
   * in an array. Verified, active flag and created date are 
   * generated automatically. 
   *    
   * @param array $data
   */
  public function exchangeArray($data)
  {
        $this->populate($data);
      
        //defaults
        $this->verified  = 0;
        $this->active    = 1;
        $this->created   = new \DateTime();
        
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