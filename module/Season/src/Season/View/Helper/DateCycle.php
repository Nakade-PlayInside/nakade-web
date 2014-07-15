<?php
namespace Season\View\Helper;

use Season\Entity\SeasonDates;
use Zend\View\Helper\AbstractHelper;

/**
 * Class DateCycle
 *
 * @package Season\View\Helper
 */
class DateCycle extends AbstractHelper
{
    /**
     * @param SeasonDates $seasonDates
     *
     * @return string
     */
    public function __invoke(SeasonDates $seasonDates)
    {
        $cycle = $this->getDateHelper()->getCycle($seasonDates->getCycle());
        $day = $this->getDateHelper()->getDay($seasonDates->getDay());

        return sprintf('%s %s, %s',
            $day,
            $seasonDates->getTime()->format('H.i'),
            $cycle
        );

    }

    /**
     * @return \Season\Schedule\DateHelper
     *
     * @throws \RuntimeException
     */
    private function getDateHelper()
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();

        if (!$sm->has('Season\Services\DateHelperService')) {
            throw new \RuntimeException(
                sprintf('Result service could not be found.')
            );
        }
        return $sm->get('Season\Services\DateHelperService');
    }
}
