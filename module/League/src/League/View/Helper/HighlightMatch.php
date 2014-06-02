<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * Class Active
 *
 * @package League\View\Helper
 */
class HighlightMatch extends AbstractHelper implements HighlightInterface
{

    /**
     * @param Match $match
     *
     * @return bool
     */
    public function __invoke(Match $match)
    {
       $color = $this->getView()->cycle(array(self::ALTERNATING_COLOR,self::BG_COLOR))->next();
       $id = $this->getView()->identity()->getId();

       if ($id == $match->getBlack()->getId() || $id == $match->getWhite()->getId()) {
            $color =  self::HIGHLIGHT_COLOR;
       }
       return $color;
    }

}
