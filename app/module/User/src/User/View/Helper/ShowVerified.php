<?php
namespace User\View\Helper;

use User\Entity\User;
use Nakade\Abstracts\AbstractViewHelper;

/**
 * shows an active/inactive image or an envelope if not expired  
 */
class ShowVerified extends AbstractViewHelper
{
    
    public function __invoke(User $user)
    {
        
        if($user===null)
            return;
          
        //default
        $placeholder = array(
            'info'   => $this->translate('not verified'),
            'image'  => '/images/inactive.png',
        );
        
        if($user->isVerified()) {
            $placeholder['info']   = $this->translate('verified');
            $placeholder['image']  = '/images/active.png';
        }
        elseif($user->isDue()) {
            $placeholder['info']   = 
                $this->translate('waiting for verification');
            $placeholder['image']  = '/images/mail.png';
        }
            
        
        $img  = '<img alt="%info%" title="%info%" src="%image%">';
        
        return $this->setPlaceholders($img, $placeholder);
            
       
    }
    
   
}
