<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
/**
 * shows an edit link image to user
 */
class ShowEdit extends AbstractViewHelper
{
    
    public function __invoke($uid, $route='user')
    {
        
        //default
        $placeholder = array(
            'info'   => $this->translate('edit'),
            'image'  => '/images/edit.png',
            'route'  => $route,
            'uid'    => $uid,
        );
        
        $link  = '<a title="%info%" href="%route%/edit/%uid%">';
        $link .= '<img alt="%info%" src="%image%"></a>';
            
        return $this->setPlaceholders($link, $placeholder);
       
       
    }
    
   
}
