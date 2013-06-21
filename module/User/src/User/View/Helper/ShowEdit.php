<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
/**
 * shows an edit link image to user
 */
class ShowEdit extends AbstractViewHelper
{
    
    public function __invoke($uid)
    {
        
        //default
        $placeholder = array(
            'info'   => $this->translate('edit'),
            'image'  => '/images/edit.png',
            'uid'    => $uid,
        );
        
        $link  = '<a title="%info%" href="user/edit/%uid%">';
        $link .= '<img alt="%info%" src="%image%"></a>';
            
        return $this->setPlaceholders($link, $placeholder);
       
       
    }
    
   
}
