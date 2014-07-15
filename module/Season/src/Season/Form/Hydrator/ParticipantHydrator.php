<?php
namespace Season\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Season\Services\RepositoryService;

/**
 * Class ParticipantHydrator
 *
 * @package Season\Form
 */
class ParticipantHydrator implements HydratorInterface
{
    private $repository;

    /**
     * @param RepositoryService $repositoryService
     */
    public function __construct(RepositoryService $repositoryService)
    {
        $this->repository = $repositoryService;
    }

    /**
     * @param \Season\Entity\Season $season
     *
     * @return array
     */
    public function extract($season)
    {
        return array(
          'number' => $season->getNumber(),
          'associationName' => $season->getAssociation()->getName(),
          'invitedPlayers' => count($this->getSeasonMapper()->getInvitedUsersBySeason($season->getId())),
        );
    }

    /**
     * @param array                 $data
     * @param \Season\Entity\Season $season
     *
     * @return object
     */
    public function hydrate(array $data, $season)
    {
        $playerList = array();
        foreach ($data['addPlayer'] as $userId) {
            $playerList[] = $this->getUserById($userId);
        }
        $season->setAvailablePlayers($playerList);

        return $season;
    }

    /**
     * @param int $userId
     *
     * @return \User\Entity\User
     */
    private function getUserById($userId)
    {
        return $this->getSeasonMapper()->getEntityManager()->getReference('User\Entity\User', $userId);
    }

    /**
     * @return \Season\Services\RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Season\Mapper\SeasonMapper
     */
    private function getSeasonMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
    }
}
