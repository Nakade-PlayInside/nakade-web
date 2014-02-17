<?php
namespace User\View\Helper;
use User\Entity\User;

/**
 * helper for editing the email adress
 */
class EditKgs extends AbstractProfileEditHelper
{
        
    public function __invoke(User $profile)
    {
        $this->_url = "profile/kgs";
        $value = $profile->getKgs();
        
        
        return $this->getLink($value);
    }
    
       
}
