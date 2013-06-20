<?php
namespace User\View\Helper;
use User\Entity\User;


class EditNick extends AbstractProfileEditHelper
{
    
    public function __invoke(User $profile)
    {
        $css_anonym = "vertical-align:middle; margin-left:10px;";
        $title = $this->getView()->translate("Incognito");     
        $img  = "<img style=\"$css_anonym\" title=\"$title\" alt=\"anonym\" src=\"/images/anonymous.png\" />";
       
        $nick = $profile->getNickname();
        $value = $profile->isAnonym()? $nick . $img : $nick; 
        
        $css_link  = self::CSS_LINK;
        $css_value = self::CSS_VALUE;
        $css_edit  = self::CSS_EDIT;
        $method    = $this->getMethod();
        
        $url = "profile/nick";
        
        return "<a href=\"$url\" title=\"$method\" style=\"$css_link\">".
               "<span style=\"$css_value\">$value</span>".
               "<span style=\"$css_edit\">$method</span>".
               "</a>";
    }
    
       
}
