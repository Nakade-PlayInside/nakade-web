<?php

namespace Season\Services;

use Season\Schedule\Schedule;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ScheduleService
 *
 * @package Season\Services
 */
class ScheduleService implements FactoryInterface
{
    private $schedule;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $repository \Season\Services\RepositoryService */
        $repository = $services->get('Season\Services\RepositoryService');

        if (is_null($repository)) {
            throw new \RuntimeException(
                sprintf('Repository service could not be found.')
            );
        }

        $this->schedule = new Schedule($repository);
        return $this;

    }

    /**
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

}
