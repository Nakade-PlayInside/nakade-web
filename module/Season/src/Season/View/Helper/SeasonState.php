<?php
namespace Season\View\Helper;

use Season\Entity\Season;
use Zend\View\Helper\AbstractHelper;

/**
 * Class SeasonState
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

        $state = $translate('in process');
        if ($season->hasEnded()) {
            $state = $translate('ended');
        } elseif ($season->hasStarted()) {
            $state = $translate('ongoing');
        } elseif ($season->hasMatches()) {
            $state = $translate('will start soon');
        }

        return $state;

    }

}
