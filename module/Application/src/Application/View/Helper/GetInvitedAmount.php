<?php
namespace Application\View\Helper;
use User\Services\RepositoryService;

/**
 * Class GetInvitedAmount
 *
 * @package Appointment\View\Helper
 */
class GetInvitedAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        $user = $this->getIdentity();
        return count($this->getMapper()->getCouponByUser($user));
    }

    /**
     * @return \User\Mapper\UserMapper
     */
    private function getMapper()
    {
        /* @var $repository \User\Services\RepositoryService */
        $repository = $this->getService('User\Services\RepositoryService');
        return $repository->getMapper(RepositoryService::USER_MAPPER);
    }


}
