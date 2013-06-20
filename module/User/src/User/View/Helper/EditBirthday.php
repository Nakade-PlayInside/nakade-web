<?php
namespace User\View\Helper;
use User\Entity\User;


class EditBirthday extends AbstractProfileEditHelper
{
    
    public function __invoke(User $profile)
    {
        
        $birthday = $profile->getBirthday();
        $value = isset($birthday)? $birthday->format('d.m.Y') : null;
        
        $css_link  = self::CSS_LINK;
        $css_value = self::CSS_VALUE;
        $css_edit  = self::CSS_EDIT;
        $method    = $this->getMethod();
        
        $url = "profile/birthday";
        
        return "<a href=\"$url\" title=\"$method\" style=\"$css_link\">".
               "<span style=\"$css_value\">$value</span>".
               "<span style=\"$css_edit\">$method</span>".
               "</a>";
    }
    
       
}
