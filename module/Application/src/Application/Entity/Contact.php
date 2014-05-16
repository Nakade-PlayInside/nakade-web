<?php
namespace Application\Entity;

use User\Entity\UserInterface;

class Contact implements UserInterface
{
    private $name;
    private $message;
    private $email;

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     *
     * @return $this
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
     * for form data
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['message'])) {
            $this->message = $data['message'];
        }
    }

    /**
     * needed for form data
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}