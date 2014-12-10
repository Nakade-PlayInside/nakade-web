<?php

namespace Stats\Services;

use Stats\Calculation\ContingencyTableFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CrossTableService
 *
 * @package Stats\Services
 */
class CrossTableService extends AbstractStatsService
{

    private $tableService;


    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->repository = $services->get('Stats\Services\RepositoryService');
        $this->tableService  = $services->get('Nakade\Services\PlayersTableService');

        return $this;
    }

    /**
     * @param int $leagueId
     *
     * @return array
     */
    private function getMatches($leagueId)
    {
        return $this->getLeagueMapper()->getMatchesByLeague($leagueId);
    }

    /**
     * @return  \League\Mapper\LeagueMapper;
     */
    private function getLeagueMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getTable($leagueId)
    {

        $matches = $this->getMatches($leagueId);
        $table = $this->getTableService()->getTable($matches);
        $crossTable = new ContingencyTableFactory($table, $matches);

       return $crossTable->getTableData();
    }

    /**
     * @return \Nakade\Services\PlayersTableService
     */
    private function getTableService()
    {
        return $this->tableService;
    }

}
