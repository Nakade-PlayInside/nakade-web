<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
use User\Entity\User;

/**
 * shows active/inactive image link to trigger the user state
 */
class ShowTrigger extends AbstractViewHelper
{
    
    public function __invoke(User $user)
    {
        if($user===null)
            return;
          
        //default
        $placeholder = array(
            'info'   => $this->translate('activate'),
            'image'  => '/images/deactivate.png',
            'action' => 'user/undelete',
        );
        
        if($user->isActive()) {
            $placeholder['info']   = $this->translate('deactivate');
            $placeholder['image']  = '/images/activate.png';
            $placeholder['action'] = 'user/delete';
        }
        
        $link  = '<a title="%info%" href="%action%/';
        $link .= $user->getId() . '">';
        $link .= '<img alt="%info%" src="%image%"></a>';
        
        return $this->setPlaceholders($link, $placeholder);
       
       
    }
    
    
   
}
