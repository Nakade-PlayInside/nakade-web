<?php
namespace Season\View\Helper;

use Season\Entity\Season;
use Zend\View\Helper\AbstractHelper;

/**
 * Class SeasonState
 *
 * representing the actual state of a new season
 *
 * @package Season\View\Helper
 */
class SeasonState extends AbstractHelper
{
    /**
     * @param Season $season
     *
     * @return string
     */
    public function __invoke(Season $season)
    {
        $translate = $this->getView()->plugin('translate');

        if (!$season->hasPlayers()) {
            return $translate('invite participants');
        }
        if (!$season->hasLeagues()) {
            return $translate('create leagues');
        }
        if (!$season->hasMatchDays()) {
            return $translate('configure match days');
        }
        if (!$season->hasMatches()) {
            return $translate('create matches');
        }
        if (!$season->hasSchedule()) {
            return $translate('create schedule');
        }
        if (!$season->isReady()) {
            return $translate('activate season');
        }
        return $translate('in process');

    }

}
