<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Permission\Entity\RoleInterface;

/**
 * Entity Class representing a User
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 */
class User extends UserModel implements UserInterface, RoleInterface
{

    /**
     * returns true if dueDate is not expired
     *
     * @return boolean
     */
    public function isDue()
    {
        if (is_null($this->getDue())) {
            return false;
        }
        return $this->getDue() > new \DateTime();
    }

    /**
     * gets nickname if existing and anonymized or
     * firstname instead
     *
     * @return string
     */
    public function getShortName()
    {
        $shortName = $this->getFirstName();
        $nick = $this->getNickname();

        if ($this->isAnonymous()  && !is_null($nick)) {
            $shortName = $nick;
        } else {
            $lastName = strtoupper($this->getLastName());
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
        if ($this->isAnonymous()  && !empty($nick)) {
            return sprintf("%s %s '%s' %s",
                $this->getTitle(),
                $this->getFirstName(),
                $nick,
                $this->getLastName()
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
        $this->getFirstName() . " " .
        $this->getLastName();
    }

    /**
     * populating data as an array.
     * key of the array is getter methods name.
     *
     * @param array $data
     */

    public function populate($data)
    {
        //todo: needed anymore?
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @param array $data
     */
    public function exchangeArray($data)
    {
        $this->populate($data);

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