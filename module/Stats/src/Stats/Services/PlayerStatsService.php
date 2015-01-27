<?php

namespace Stats\Services;

use Stats\Calculation\PlayerMatchStats\PlayerMatchStatsFactory;
use Stats\Calculation\PlayerStatsFactory;
use Stats\Entity\PlayerStats;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PlayersStatsService
 *
 * @package Stats\Services
 */
class PlayerStatsService extends AbstractStatsService
{

    private $standings;
    private $achievement;
    private $factory;

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
        $this->standings  = $services->get('Nakade\Services\PlayersTableService');
        $this->achievement  = $services->get('Stats\Services\AchievementService');

        return $this;
    }

    /**
     * @return \Nakade\Services\PlayersTableService
     */
    public function  getPlayerTableService()
    {
        return $this->standings;
    }

    /**
     * @param $userId
     *
     * @return PlayerStats
     */
    public function getPlayerStats($userId)
    {
        $matches = $this->getMapper()->getConsecutiveMatchesByUser($userId);

        $stats = $this
            ->getFactoryByUser($userId)
            ->doEvaluation($matches)
            ->getPlayerStats();

        $tournaments = $this->getMapper()->getTournamentsByUser($userId);
        $stats->setTournaments($tournaments);

        $this->getAchievement()->getAchievements($userId);
        $data = $this->getAchievement()->getFactory()->getData();
        $stats->exchangeArray($data);

        return $stats;
    }

    /**
     * @param int $userId
     *
     * @return PlayerStatsFactory
     */
    public function getFactoryByUser($userId)
    {
        if (is_null($this->factory)) {
            $playerStatsFactory = new PlayerMatchStatsFactory($userId);
            $this->factory = new PlayerStatsFactory($playerStatsFactory);
        }
        return $this->factory;
    }

    /**
     * @return \Stats\Services\AchievementService
     */
    public function getAchievement()
    {
        return $this->achievement;
    }
}
