<?php
namespace User\View\Helper;
use User\Entity\User;

/**
 * Helper for editing the password.
 * Provides an information when the password was editied the laste time
 */
class EditPassword extends AbstractProfileEditHelper
{
    
    protected $_vars = array('pwdChangeDate' => 'pwdChangeDate',);
    protected $pwdChangeDate;
    
    public function __invoke(User $profile)
    {
        
        $this->pwdChangeDate= $this->convertDate($profile->getPwdChange());
        $this->_url = "profile/password";
        
        $value = isset($this->pwdChangeDate)? 
            $this->getMessage(self::PWD_CHANGE) : $this->getMessage(self::PWD_NEVER);
        
        //set pwd style
        $this->setStyle(self::CSS_VALUE, self::CSS_PWD);
        
        return $this->getLink($value);
    }
    
       
}
