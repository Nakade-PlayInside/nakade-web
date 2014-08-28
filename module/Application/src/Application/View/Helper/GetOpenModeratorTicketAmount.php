<?php
namespace Application\View\Helper;
use \Support\Services\RepositoryService;

/**
 * Class GetAppointmentAmount
 *
 * @package Application\View\Helper
 */
class GetOpenModeratorTicketAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        $userId = $this->getIdentity()->getId();

        return count($this->getMapper()->getNewTicketsByManager($userId));
    }

    /**
     * @return \Support\Mapper\TicketMapper
     */
    private function getMapper()
    {
        /* @var $repository \Support\Services\RepositoryService */
        $repository = $this->getService('Support\Services\RepositoryService');
        return $repository->getMapper(RepositoryService::TICKET_MAPPER);
    }

}
