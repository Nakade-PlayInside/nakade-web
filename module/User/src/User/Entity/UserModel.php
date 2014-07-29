<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Permission\Entity\RoleInterface;

//todo: nick, email, username, kgs unique
//todo: anpassung datenbank
/**
 * @ORM\MappedSuperclass
 */
class UserModel implements RoleInterface
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
     * @ORM\Column(name="titel", length=10, type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="vorname", length=20, type="string", nullable=false)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="nachname", length=30, type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="nick", length=20, unique=true, type="string")
     */
    protected $nickname;

    /**
     * @ORM\Column(name="sex", type="string")
     */
    protected $sex;

    /**
     * @ORM\Column(name="geburtsdatum", type="date")
     */
    protected $birthday;

    /**
     * @ORM\Column(name="anonym", type="boolean")
     */
    protected $anonymous=0;

    /**
     * @ORM\Column(name="username", length=50, unique=true, type="string", nullable=false)
     */
    protected $username;

    /**
     * @ORM\Column(name="password", length=80, type="string", nullable=false)
     */
    protected $password;

    /**
     * @ORM\Column(name="kgs", length=50, unique=true, type="string")
     */
    protected $kgs;

    /**
     * @ORM\Column(name="email", length=120, unique=true, type="string", nullable=false)
     */
    protected $email;

    /**
     * @ORM\Column(name="verifyString", length=32, type="string")
     */
    protected $verifyString;

    /**
     * @ORM\Column(name="verified", type="boolean")
     */
    protected $verified=0;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active=1;

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
     * @ORM\Column(name="role", length=15, type="string")
     */
    protected $role=RoleInterface::ROLE_GUEST;

    /**
     * @ORM\Column(name="language", type="string")
     */
    protected $language;


    /**
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
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
     * @return \DateTime
     */
    public function getEdit()
    {
        return $this->edit;
    }

    /**
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