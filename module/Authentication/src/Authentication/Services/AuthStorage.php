<?php

//module/Authentication/src/Authentication/Services/AuthStorage.php
namespace Authentication\Services;

use Zend\Authentication\Storage;

/**
 * Handling the session lifetime. By default session lifetime is set to 14 days.
 * 
 * 
 */
class AuthStorage extends Storage\Session
{
    
    /**
     * constructor sets the namespace for session to nakade
     */
    public function __construct() {
        parent::__construct($namespace = 'nakade', null , null);
    }


    /**
     * Sets the lifetime of a session by a flag. Default lifetime is 14d. 
     * Life time is set in seconds.
     *    
     * @param boolean $rememberMe
     * @param int $time
     */
    public function setRememberMe($rememberMe = FALSE, $time = 1209600)
    {
         if ($rememberMe == TRUE) {
             $this->session->getManager()->rememberMe($time);
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
     * This method is called by the Zend Authentication Service
     * during logOut.
     */
    public function clear()
    {
    
        $this->forgetMe();
        parent::clear();
        
    }
}