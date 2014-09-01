<?php
namespace Authentication\Entity;


/**
 * Class Login
 *
 * @package Authentication\Entity
 */
class Login
{

    private $identity;
    private $password;
    private $rememberMe=false;

    /**
     * @param string $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param bool $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }

    /**
     * @return bool
     */
    public function isRememberMe()
    {
        return $this->rememberMe;
    }




}