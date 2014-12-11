<?php
namespace Stats\Entity;
use User\Entity\User;

/**
 * Class PlayerResult
 *
 * @package Stats\Entity
 */
class PlayerResult
{
    private $user;
    private $results = array();

    /**
     * @param User $user
     * @param array $results
     */
    public function __construct(User $user, array $results)
    {
        $this->setUser($user);
        $this->setResults($results);
    }

    /**
     * @param array $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }



}