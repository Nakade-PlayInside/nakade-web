<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
/**
 * shows an reset pwd link image to user
 */
class ShowPwdReset extends AbstractViewHelper
{
    
    public function __invoke($uid)
    {
        
        //default
        $placeholder = array(
            'info'   => $this->translate('reset password'),
            'image'  => '/images/key_add.png',
            'uid'    => $uid,
        );
        
        $link  = '<a title="%info%" href="user/resetPassword/%uid%">';
        $link .= '<img alt="%info%" src="%image%" width="16px" height="16px">';
        $link .= '</a>';
            
        return $this->setPlaceholders($link, $placeholder);
       
       
    }
    
   
}
