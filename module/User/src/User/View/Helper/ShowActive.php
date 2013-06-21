<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
/**
 * shows an active/inactive image  
 */
class ShowActive extends AbstractViewHelper
{
    
    public function __invoke($active)
    {
        
        //default
        $placeholder = array(
            'info'   => $this->translate('inactive'),
            'image'  => '/images/inactive.png',
        );
        
        if($active) {
            $placeholder['info']   = $this->translate('active');
            $placeholder['image']  = '/images/active.png'; 
        }
        
        $img = '<img alt="%info%" title=%info% src="%image%">';
        
        return $this->setPlaceholders($img, $placeholder);
       
       
    }
    
   
}
