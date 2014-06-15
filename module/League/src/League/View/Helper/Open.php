<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * Class Open
 *
 * @package League\View\Helper
 */
class Open extends AbstractHelper implements HighlightInterface
{
    /**
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
        $today = new \DateTime();
        $color = $this->getView()->cycle(array(self::ALTERNATING_COLOR,self::BG_COLOR))->next();
        if (is_null($match->getResult()) && $match->getDate() < $today) {
            $color =  self::HIGHLIGHT_COLOR;
        }
        return $color;

    }
}
