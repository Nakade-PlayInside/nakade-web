<?php
namespace Application\View\Helper;
use Appointment\Services\RepositoryService;

/**
 * Class GetAppointmentAmount
 *
 * @package Application\View\Helper
 */
class GetAppointmentAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        $user = $this->getIdentity();
        return count($this->getMapper()->getOpenConfirmsByUser($user));
    }

    /**
     * @return \Appointment\Mapper\AppointmentMapper
     */
    private function getMapper()
    {
        /* @var $repository \Appointment\Services\RepositoryService */
        $repository = $this->getService('Appointment\Services\RepositoryService');
        return $repository->getMapper(RepositoryService::APPOINTMENT_MAPPER);
    }

}
