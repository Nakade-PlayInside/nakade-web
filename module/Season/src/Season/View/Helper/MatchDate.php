<?php
namespace Season\View\Helper;

use Season\Entity\Season;
use Zend\View\Helper\AbstractHelper;

/**
 * Class MatchDates
 *
 * @package Season\View\Helper
 */
class MatchDate extends AbstractHelper
{
    /**
     * @param \DateTime $matchDate
     *
     * @return string
     */
    public function __invoke(\DateTime $matchDate = null)
    {
        if (is_null($matchDate)) {
            return '--';
        }
        return $matchDate->format('d.m.Y H:i');

    }
}
