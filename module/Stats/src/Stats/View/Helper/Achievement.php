<?php
namespace Stats\View\Helper;

use Season\Entity\League;
use Zend\View\Helper\AbstractHelper;

/**
 * Class AchievementTitle
 *
 * @package Stats\View\Helper
 */
class Achievement extends AbstractHelper
{

    /**
     * @param League $tournament
     * @param int $position
     *
     * @return string
     */
    public function __invoke(League $tournament, $position)
    {
        $achievement = '';
        if ($this->hasAchievement($position)) {

            $achievement = sprintf('<div class="nakade-16 %s-%s-16"></div>',
                $this->getType($tournament),
                $this->getAchievement($position)
            );
        }

        return $achievement;
     }

    private function getType(League $tournament)
    {
            $type = $tournament->getNumber();

            if ($type == 1) {
                return 'crown';
            }
            elseif ($type > 1) {
                return 'medal';
            }

            return 'cup';

    }

    private function hasAchievement($position)
    {
        return $position >= 1 && $position <= 3;
    }

    private function getAchievement($position)
    {
        switch ($position) {
            case AchievementTitle::CHAMPION:
                $achievement = 'gold';
                break;
            case AchievementTitle::RUNNER_UP:
                $achievement = 'silver';
                break;
            case AchievementTitle::THIRD:
                $achievement = 'bronze';
                break;
            default: $achievement = '';
        }

        return $achievement;
    }


}
