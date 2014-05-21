<?php
namespace Authentication\Adapter;

use Zend\Authentication\Storage;

/**
 * Handling the session lifetime. By default session lifetime is set to 14 days.
 * This storage is based on the Login Example by Abdul Malik Iksan.
 * For further information see his blog https://samsonasik.wordpress.com/
 *
 */
class AuthStorage extends Storage\Session
{
    //in seconds
    private $cookieLifeTime=1209600;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct('nakade', null, null);
    }


    /**
     * Sets the lifetime of a session by a flag. Default lifetime is 14d.
     * Life time is set in seconds.
     *
     * @param boolean $rememberMe
     */
    public function setRememberMe($rememberMe=false)
    {
        if ($rememberMe) {
            $this->session->getManager()->rememberMe($this->cookieLifeTime);
        }
    }

    /**
     * deletes the session cookie
     *
     */
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }

    /**
     * unsets the session and destroys the cookie.
     * This method is called by the Zend Authentication Services
     * during logOut.
     */
    public function clear()
    {

        $this->forgetMe();
        parent::clear();

    }

    /**
     * @param int $cookieLifeTime
     */
    public function setCookieLifeTime($cookieLifeTime)
    {
        $this->cookieLifeTime = $cookieLifeTime;
    }

}
