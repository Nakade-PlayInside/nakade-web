<?php
namespace Stats\View\Helper;

use Season\Entity\League;

/**
 * Class AchievementTitle
 *
 * @package Stats\View\Helper
 */
class AchievementTitle extends Achievement
{
    /**
     * @param League $tournament
     * @param int $position
     *
     * @return string
     */
    public function __invoke(League $tournament, $position)
    {

        return $this->getTitle($position) . ' - ' .
            $tournament->getSeason()->getNumber() . '. ' .
            $tournament->getSeason()->getAssociation()->getName() . ' ' .
            $this->translate('League');
    }

}
