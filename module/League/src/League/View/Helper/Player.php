<?php
namespace League\View\Helper;
/**
 * Determines the player's name. If the player has a nickname set as well as 
 * wants to be anonym, the nickname is shown instead of the forname. 
 * 
 */

use Zend\View\Helper\AbstractHelper;
 
class Player extends AbstractHelper
{
    
    /**
     * 
     * @param User\Entity\Player $player
     * @return string
     */
    public function __invoke($player)
    {
        
        if ($player->getNickName()&& 
            $player->isAnonym()) {
            return $player->getNickName();
        }
        
        return $player->getFirstName();
       
    }
    
   
}
