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
class User extends UserModel implements UserInterface
{

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
        $temp = empty($title)? null:$title;
        $this->title = $temp;
        return $this;
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
        $temp = empty($nickname)? null:$nickname;
        $this->nickname = $temp;
        return $this;
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
        $temp = empty($datetime)? null : $datetime;
        if (is_string($temp)) {
            $temp = new \DateTime($temp);
        }
        $this->birthday = $temp;
        return $this;
    }

    /**
     * returns true if dueDate is not expired
     *
     * @return boolean
     */
    public function isDue()
    {
        $expired = $this->getDue();
        if ($expired===null) {
            return false;
        }

        return $expired > new \DateTime();
    }

    /**
     * gets nickname if existing and anonymized or
     * firstname instead
     *
     * @return string
     */
    public function getShortName()
    {
        $shortName = $this->getFirstname();
        $nick = $this->getNickname();

        if ($this->isAnonym()  && !is_null($nick)) {
            $shortName = $nick;
        } else {
            $lastName = strtoupper($this->getLastname());
            $shortName .= " " . $lastName[0] . ".";
        }

        return $shortName;
    }

    /**
     * online Go Name
     *
     * @return string
     */
    public function getOnlineName()
    {
        if (is_null($this->getKgs())) {
            return $this->getShortName();
        }
        return $this->getKgs();
    }

    /**
     * get complete name with nickname if existing
     *
     * @return string
     */
    public function getCompleteName()
    {
        $nick = $this->getNickname();
        if ($this->isAnonym()  && !empty($nick)) {
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
     *
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
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
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