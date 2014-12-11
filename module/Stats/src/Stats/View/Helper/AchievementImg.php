<?php
namespace Stats\View\Helper;

use Season\Entity\League;
/**
 * Class AchievementImg
 *
 * @package Stats\View\Helper
 */
class AchievementImg extends Achievement
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
        if (!$tournament->isOngoing() && $this->hasAchievement($position)) {

            $achievement = sprintf('<div title="%s" class="nakade-16 %s-%s-16"></div>',
                $this->getTitle($position),
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
            case self::CHAMPION:
                $achievement = 'gold';
                break;
            case self::RUNNER_UP:
                $achievement = 'silver';
                break;
            case self::THIRD:
                $achievement = 'bronze';
                break;
            default: $achievement = '';
        }

        return $achievement;
    }


}
