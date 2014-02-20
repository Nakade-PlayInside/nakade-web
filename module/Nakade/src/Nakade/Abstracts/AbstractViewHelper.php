<?php
namespace Nakade\Abstracts;

use Zend\View\Helper\AbstractHelper;

/**
 * Extending for having a view helper.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractViewHelper extends AbstractHelper {
   

    /**
     * converts a DateTime object into a string. Use this 
     * to format MySQL date values 
     *  
     * @param DateTime $date
     * @return string
     */
    public function convertDate($date)
    {
        return isset($date)? $date->format('d.m.Y') : null;
    }
    
    
    /**
     * Replaces all matching placeholders in a message
     * 
     * @param string $message
     * @param array $placeholder
     * @return string
     */
    public function setPlaceholders($message, $placeholder)
    {
        foreach ($placeholder as $ident => $property) {
            $message = str_replace("%$ident%", (string) $property, $message);
        }
        return  $message;
    }
    
    /**
     * Translate the provided message
     * 
     * @param string $message
     * @return string
     */
    public function translate($message)
    {
        return $this->getView()->translate($message);
    }
    
}

?>
