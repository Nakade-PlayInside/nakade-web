<?php
namespace Permission\Services;


/**
 * Interface VoteInterface
 *
 * @package Permission\Service
 */
interface VoterInterface
{
    /**
     * @return bool
     */
    public function isAdmin();

    /**
     * @return bool
     */
    public function isModerator();

    /**
     * @return bool
     */
    public function isMember();

    /**
     * @return bool
     */
    public function isUser();

    /**
     * @return bool
     */
    public function isGuest();

    /**
     * @return bool
     */
    public function isLeagueOwner();

    /**
     * @return bool
     */
    public function isLeagueManager();

    /**
     * @return bool
     */
    public function isReferee();

    /**
     * is admin, referee or league manager
     *
     * @return bool
     */
    public function isManager();

}