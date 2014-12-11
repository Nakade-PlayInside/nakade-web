<?php
namespace Stats\View\Helper;

use Season\Entity\League;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Achievement
 *
 * @package Stats\View\Helper
 */
abstract class Achievement extends AbstractHelper
{
    const CHAMPION = 1;
    const RUNNER_UP = 2;
    const THIRD = 3;

    /**
     * @param League $tournament
     * @param int $position
     *
     * @return string
     */
    abstract public function __invoke(League $tournament, $position);


    protected function getTitle($position)
    {

        switch ($position) {
            case AchievementTitle::CHAMPION:
                $title = $this->translate('Champion');
                break;
            case AchievementTitle::RUNNER_UP:
                $title = $this->translate('Runner-Up');
                break;
            case AchievementTitle::THIRD:
                $title = $this->translate('Third');
                break;
            default: $title = $position . '.';
        }

        return $title;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    protected function translate($message)
    {
        $translate = $this->getView()->plugin('translate');
        return $translate($message);
    }


}
