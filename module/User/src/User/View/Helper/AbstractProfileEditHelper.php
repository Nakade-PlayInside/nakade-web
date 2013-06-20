<?php
namespace User\View\Helper;

use User\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * Extending for having a helper for profile editing
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractProfileEditHelper extends AbstractHelper {
   
    const CSS_LINK  = "display:block; cursor:pointer; text-decoration:none; background-color:transparent; width:100%"; 
    const CSS_VALUE = "color:#333333;";
    const CSS_EDIT  = "padding-left:30px; float:right;";
    
    
    protected function getMethod()
    {
        return $this->getView()->translate('edit');
    }
    
    abstract public function __invoke(User $profile);
}

?>
