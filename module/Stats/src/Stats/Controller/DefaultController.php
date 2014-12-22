<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Nakade\Services\PlayersTableService;
use Nakade\Webservice\EGDService;
use Stats\Calculation\MatchStatsFactory;
use Stats\Services\CertificateService;
use Stats\Services\CrossTableService;
use Stats\Entity\PlayerStats;
use Stats\Services\RepositoryService;
use Stats\Mapper\StatsMapper;
use League\Mapper\LeagueMapper;

/**
 *
 * @package Stats\Controller
 */
class DefaultController extends AbstractController
{
    protected $tableService;
    protected $crossTableService;
    protected $certificateService;
    protected $egdService;


    /**
     * @param PlayersTableService $tableService
     */
    public function setTableService(PlayersTableService $tableService)
    {
        $this->tableService = $tableService;
    }

    /**
     * @return PlayersTableService
     */
    public function getTableService()
    {
        return $this->tableService;
    }

    /**
     * @param CrossTableService $crossTableService
     */
    public function setCrossTableService(CrossTableService $crossTableService)
    {
        $this->crossTableService = $crossTableService;
    }

    /**
     * @return CrossTableService
     */
    public function getCrossTableService()
    {
        return $this->crossTableService;
    }

    /**
     * @param CertificateService $certificateService
     */
    public function setCertificateService(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * @return CertificateService
     */
    public function getCertificateService()
    {
        return $this->certificateService;
    }

    /**
     * @return PlayerStats
     */
    public function getPlayerStats()
    {
        $userId = $this->identity()->getId();
        return $this->getService()->getPlayerStats($userId);
    }

    /**
     * @return StatsMapper
     */
    public function getStatsMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);
    }

    /**
     * @return LeagueMapper
     */
    public function getLeagueMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

    /**
     * @return \Stats\Entity\MatchStats
     */
    public function getMatchStats()
    {
        $userId = $this->identity()->getId();

        $matches = $this->getStatsMapper()->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        return $factory->getMatchStats();
    }

    /**
     * @param EGDService $egdService
     */
    public function setEgdService(EGDService $egdService)
    {
        $this->egdService = $egdService;
    }

    /**
     * @return EGDService
     */
    public function getEgdService()
    {
        return $this->egdService;
    }


}
