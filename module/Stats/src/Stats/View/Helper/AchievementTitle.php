<?php
namespace Stats\View\Helper;

use Season\Entity\League;
use Zend\View\Helper\AbstractHelper;

/**
 * Class AchievementTitle
 *
 * @package Stats\View\Helper
 */
class AchievementTitle extends AbstractHelper
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
    public function __invoke(League $tournament, $position)
    {

        return $this->getTitle($position) . ' - ' .
            $tournament->getSeason()->getNumber() . '. ' .
            $tournament->getSeason()->getAssociation()->getName() . ' ' .
            $this->translate('League');
    }

    private function getTitle($position)
    {

        switch ($position) {
            case self::CHAMPION:
                $title = $this->translate('Champion');
                break;
            case self::RUNNER_UP:
                $title = $this->translate('Runner-Up');
                break;
            case self::THIRD:
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
    private function translate($message)
    {
        $translate = $this->getView()->plugin('translate');
        return $translate($message);
    }

}
