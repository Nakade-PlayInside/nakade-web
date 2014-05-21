<?php
namespace Authentication\Session;

use Zend\Session\Container;

/**
 * The amount of failed authentication attempts are collected in a session.
 * Basically, this is used for showing up a Captcha after too many failed
 * attempts. You can still access the data as usual: $_SESSION[name][key]
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class FailureContainer extends Container
{
    private $attempts = 0;
    private $maxAttempts = 0;

    /**
     * @param int $maxAttempts
     */
    public function __construct($maxAttempts=0)
    {
       $this->maxAttempts = $maxAttempts;
       parent::__construct('FailedAuthAttempts');
    }


    /**
     * Sets the amount of failed authentication, adding one
     * each time the method is called.
     *
     */
    public function addFailedAttempt()
    {
         $this->attempts++;
    }

    /**
     * Resets the session to 0.
     */
    public function clear()
    {
        $this->attempts=0;
    }

    /**
     * @return bool
     */
    public function hasExceededAllowedAttempts()
    {
        return $this->attempts >= $this->maxAttempts;
    }


}
