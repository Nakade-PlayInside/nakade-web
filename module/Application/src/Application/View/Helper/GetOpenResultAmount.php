<?php
namespace Application\View\Helper;
use League\Services\RepositoryService;

/**
 * Class GetOpenResultAmount
 *
 * @package Application\View\Helper
 */
class GetOpenResultAmount extends DefaultViewHelper
{

    /**
     * @return int
     */
    public function __invoke()
    {
        $uid = $this->getIdentity()->getId();
        return count($this->getMapper()->getOpenResultByUser($uid));
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
