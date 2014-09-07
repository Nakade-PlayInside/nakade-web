<?php
namespace Application\View\Helper;
use League\Services\RepositoryService;

/**
 * Class GetAppointmentAmount
 *
 * @package Application\View\Helper
 */
class GetNextMatch extends DefaultViewHelper
{

    /**
     * @return \DateTime
     */
    public function __invoke()
    {
        $uid = $this->getIdentity()->getId();
        return $this->getMapper()->getNextMatchDateByUser($uid);
    }

    /**
     * @return \League\Mapper\ScheduleMapper
     */
    private function getMapper()
    {
        /* @var $repository \League\Services\RepositoryService */
        $repository = $this->getService('League\Services\RepositoryService');
        return $repository->getMapper(RepositoryService::SCHEDULE_MAPPER);
    }

}
