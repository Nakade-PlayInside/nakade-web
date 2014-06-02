<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * Class Active
 *
 * @package League\View\Helper
 */
class Highlight extends AbstractHelper
{
    private $userColor = "#FFC4C4";
    private $alternate = "#F0F0F0";


    /**
     * @param Match $match
     *
     * @return bool
     */
    public function __invoke(Match $match)
    {
       $color = $this->getView()->cycle(array($this->alternate,"transparent"))->next();
       $id = $this->getView()->identity()->getId();

       if ($id == $match->getBlack()->getId() || $id == $match->getWhite()->getId()) {
            $color =  $this->userColor;
       }
       return $color;
    }

}
