<?php
namespace Permission\View\Helper;

/**
 * Class Voter
 *
 * @package Permission\View\Helper
 */
class Voter extends DefaultViewHelper
{

    /**
     * if a given role and its parents has access
     *
     * @param $role
     *
     * @return bool
     */
    public function __invoke($role)
    {
       if(empty($role)) {
           return true;
       }

       switch ($role) {
           case self::ROLE_ADMIN:
               $isAllowed = $this->getVoterService()->isAdmin();
               break;
           case self::ROLE_MODERATOR:
               $isAllowed = $this->getVoterService()->isModerator();
               break;
           case self::ROLE_MEMBER:
               $isAllowed = $this->getVoterService()->isMember();
               break;
           case self::ROLE_USER:
               $isAllowed = $this->getVoterService()->isUser();
               break;
           case self::ROLE_GUEST:
               $isAllowed = $this->getVoterService()->isGuest();
               break;
           case self::ROLE_REFEREE:
               $isAllowed = $this->getVoterService()->isReferee();
               break;
           case self::ROLE_LEAGUE_OWNER:
               $isAllowed = $this->getVoterService()->isLeagueOwner();
               break;
           case self::ROLE_LEAGUE_MANAGER:
               $isAllowed = $this->getVoterService()->isLeagueManager();
               break;
           case self::ROLE_MANAGER:
               $isAllowed = $this->getVoterService()->isManager();
               break;
           default: $isAllowed = false;
       }
        return $isAllowed;
    }

}
