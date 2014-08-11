<?php

namespace Moderator\Services;

use League\Pagination\MatchDayPagination;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractService;

/**
 * Class PaginationService
 *
 * @package League\Services
 */
class PaginationService extends AbstractService
{
    /* @var $pagination MatchDayPagination */
    private $pagination;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $repoSrv \League\Services\RepositoryService */
        $repoSrv = $services->get('League\Services\RepositoryService');
        $mapper = $repoSrv->getMapper(RepositoryService::RESULT_MAPPER);

        $this->pagination = new MatchDayPagination($mapper);

        return $this;
    }

    /**
     * @param int $leagueId
     * @param int $current
     *
     * @return \Zend\Paginator\Paginator
     */
    public function getPagination($leagueId, $current=1)
    {
        return $this->pagination->getPagination($leagueId, $current);
    }


}
