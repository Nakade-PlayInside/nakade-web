<?php
namespace Authentication\Session;

use Zend\Session\AbstractContainer;

/**
 * Class FailureContainer
 *
 * @package Authentication\Session
 */
class FailureContainer extends AbstractContainer
{
    const ATTEMPT = 'attempt';
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
     * @return int
     */
    public function addFailedAttempt()
    {
        $attempts = $this->getFailedAttempt() + 1;
        $this->offsetSet(self::ATTEMPT, $attempts);

        return $attempts;
    }

    /**
     * @return int
     */
    public function getFailedAttempt()
    {
        $attempts = 0;
        if ($this->offsetExists(self::ATTEMPT)) {
            $attempts = intval($this->offsetGet(self::ATTEMPT));
        } else {
            $this->offsetSet(self::ATTEMPT, $attempts);
        }
        return $attempts;
    }

    /**
     * Resets the session to 0.
     */
    public function clear()
    {
        $this->offsetSet(self::ATTEMPT, 0);
    }

    /**
     * @return bool
     */
    public function hasExceededAllowedAttempts()
    {
        return $this->getFailedAttempt() >= $this->getMaxAttempts();
    }

    /**
     * @return int
     */
    public function getMaxAttempts()
    {
        return $this->maxAttempts;
    }


}
