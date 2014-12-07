<?php

namespace Stats\Services;

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
        $tournaments = $this->getMapper()->getTournamentsByUser($userId);
        $factory = $this->getPlayerStatsFactory($userId);

        $stats = new PlayerStats();
        $stats->setTournaments($tournaments);
        $stats->setMatches($factory->getMatches());
        $stats->setPlayed($factory->getMatches());
        $stats->setWins($factory->getWin());
        $stats->setConsecutiveWins($factory->getMaxConsecutiveWins());
        $stats->setLoss($factory->getDefeat());
        $stats->setDraws($factory->getDraw());

        $this->getAchievement()->getAchievements($userId);
        $stats->setChampion($this->getAchievement()->getFactory()->getChampion());
        $stats->setMedal($this->getAchievement()->getFactory()->getMedal());
        $stats->setCup($this->getAchievement()->getFactory()->getCup());

        return $stats;
    }

    /**
     * @param int $userId
     *
     * @return PlayerStatsFactory
     */
    private function getPlayerStatsFactory($userId)
    {
        $matches = $this->getMapper()->getConsecutiveMatchesByUser($userId);
        return new PlayerStatsFactory($matches, $userId);
    }

    /**
     * @return \Stats\Services\AchievementService
     */
    public function getAchievement()
    {
        return $this->achievement;
    }
}
