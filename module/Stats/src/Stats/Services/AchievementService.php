<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Services;

use Season\Entity\League;
use Stats\Calculation\AchievementStatsFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AchievementService
 *
 * @package Stats\Services
 */
class AchievementService extends AbstractStatsService
{
    private $standings;
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
        $this->factory = new AchievementStatsFactory();

        return $this;
    }

    public function getAchievements($userId)
    {

        $tournaments = $this->getMapper()->getTournamentsByUser($userId);
        foreach ($tournaments as $tournament)
        {
            $this->evalTournament($tournament, $userId);
        }

    }

    /**
     * @param array $matches
     *
     * @return bool
     */
    public function isOngoing(array $matches)
    {
        /* @var $match \Season\Entity\Match */
        foreach ($matches as $match) {

            if (!$match->hasResult()) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param League $tournament
     * @param int $userId
     */
    private function evalTournament(League $tournament, $userId)
    {
        $matches = $this->getMapper()->getMatchesByTournament($tournament->getId());
        if ($this->isOngoing($matches)) {
            return;
        }

        $table = $this->getPlayerTableService()->getTable($matches);

        /* @var  $player \League\Entity\Player */
        foreach ($table as $player) {

            if ($player->getUser()->getId() == $userId) {
                $this->getFactory()->evalAchievement($tournament, $player->getPosition());
            }
        }
    }

    /**
     * @return \Nakade\Services\PlayersTableService
     */
    public function  getPlayerTableService()
    {
        return $this->standings;
    }

    /**
     * @return AchievementStatsFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }




}