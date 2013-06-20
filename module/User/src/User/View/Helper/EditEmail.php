<?php
namespace User\View\Helper;
use User\Entity\User;


class EditEmail extends AbstractProfileEditHelper
{
        
    public function __invoke(User $profile)
    {
        $value = $profile->getEmail(); 
        
        $css_link  = self::CSS_LINK;
        $css_value = self::CSS_VALUE;
        $css_edit  = self::CSS_EDIT;
        $method    = $this->getMethod();
        
        $url = "profile/email";
        
        return "<a href=\"$url\" title=\"$method\" style=\"$css_link\">".
               "<span style=\"$css_value\">$value</span>".
               "<span style=\"$css_edit\">$method</span>".
               "</a>";
    }
    
       
}
