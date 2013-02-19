<?php
namespace League\View\Helper;
/**
 * Determines the position of players. Usally the position is given by the 
 * order of database request. Player who have not played games are given an 
 * even position.  
 */

use Zend\View\Helper\AbstractHelper;
 
class Player extends AbstractHelper
{
    /**
     * Position 
     * @var int
     * @access protected
     *  
     */
    protected $_position = 1;
    
    /**
     * flag  
     * @var bool
     * @access protected
     *  
     */
    protected $_isFirstNoGames=TRUE;
 
    public function __invoke($player)
    {
        
        if ($player->getPlayer()->getNickName()&& 
            $player->getPlayer()->isAnonym()) {
            return $player->getPlayer()->getNickName();
        }
        
        return $player->getPlayer()->getFirstName();
       
    }
    
   
}
