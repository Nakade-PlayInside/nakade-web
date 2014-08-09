<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use Season\Entity\Season;
use \Doctrine\ORM\Query;

/**
 * Class SeasonMapper
 *
 * @package Season\Mapper
 */
class SeasonMapper extends AbstractMapper
{
     /**
     * @param int $id
     *
     * @return object
     */
    public function getAssociationById($id)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Association')
            ->find($id);
    }

    /**
     * @param int $matchId
     *
     * @return \Season\Entity\MatchDay
     */
    public function getMatchDayById($matchId)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\MatchDay')
            ->find($matchId);
    }

    /**
     * active season has already started and the isReady flag is set
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getActiveSeasonByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->where('s.isReady = 1')
            ->andWhere('s.association = :association')
            ->andWhere('s.startDate < :start')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId)
            ->setParameter('start', new \DateTime());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $seasonId
     *
     * @return \Season\Entity\Season
     */
    public function getSeasonById($seasonId)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Season')
            ->find($seasonId);
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getNewSeasonsByUser($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->where('s.isReady = false')
            ->addOrderBy('s.startDate', 'DESC');

        $result = $qb->getQuery()->getResult();

        /* @var $season \Season\Entity\Season */
        foreach ($result as $season) {
            $data = $this->getSeasonInfo($season->getId());
            $data['isRegistered'] = $this->isUserParticipatingInSeason($userId, $season->getId());

            $season->exchangeArray($data);
        }
        return $result;
    }

    /**
     * new season has not yet started
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getNewSeasonByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->where('s.association = :association')
            ->andWhere('s.isReady = 0')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId);

        $season = $qb->getQuery()->getOneOrNullResult();

        /* @var $season \Season\Entity\Season */
        if (!empty($season)) {
            $data = $this->getSeasonInfo($season->getId());
            $season->exchangeArray($data);
        }

        return $season;
    }

    /**
     * @param int $associationId
     *
     * @return bool
     */
    public function hasNewSeasonByAssociation($associationId=1)
    {
        return !is_null($this->getNewSeasonByAssociation($associationId));
    }

    /**
     * last season has no open matches. It's the last season played!
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getLastSeasonByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->where('s.isReady = 1')
            ->andWhere('s.association = :association')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * returns a mapped array of season info data
     *
     * @param int $seasonId
     *
     * @return null|array
     */
    public function getSeasonInfo($seasonId)
    {
      $data = null;
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('min(m.date) as firstMatchDate,
            max(m.date) as lastMatchDate')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('Season\Entity\Match', 'm', Join::WITH, 'l = m.league')
            ->where('s.id = :seasonId')
            ->setParameter('seasonId', $seasonId);
        $data = $qb->getQuery()->getOneOrNullResult();

        if (!empty($data)) {
            $data['openMatches'] = $this->getOpenMatchesInSeason($seasonId);
            $data['matches'] = $this->getMatchesBySeason($seasonId);
            $data['leagues'] = $this->getLeaguesInSeason($seasonId);
            $data['players'] = $this->getPlayersInSeason($seasonId);
            $data['matchDays'] = $this->getMatchDaysBySeason($seasonId);
            $data['availablePlayers'] = $this->getAvailablePlayersBySeason($seasonId);
            $data['unassignedPlayers'] = $this->getUnassignedParticipantsBySeason($seasonId);
        }

        return $data;
    }

    /**
     * @param int $seasonId
     *
     * @return \DateTime
     *
     * @throws \RuntimeException
     */
    public function getLastMatchDateOfSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('max(m.date) as lastMatchDate')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('Season\Entity\Match', 'm', Join::WITH, 'l = m.league')
            ->where('s.id = :id')
            ->setParameter('id', $seasonId);
        $result = $qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR);
        if (is_null($result)) {
            throw new \RuntimeException(
                sprintf('No match date found! Check season with id=%s.', $seasonId)
            );
        }
        return \DateTime::createFromFormat('Y-m-d H:i:s', $result);
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getOpenMatchesInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Match');
        $qb->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->innerJoin('l.season', 'mySeason')
            ->where('mySeason.id = :seasonId')
            ->andWhere('m.result IS NULL')
            ->setParameter('seasonId', $seasonId);
        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getLeaguesInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from('Season\Entity\League', 'l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->where('s.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getPlayersInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Player');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'p.season = s')
            ->where('s.id = :seasonId')
            ->andWhere('p.league IS NOT NULL')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getTieBreaker()
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\TieBreaker')
            ->findAll();
    }

    /**
     * @return array
     */
    public function getByoyomi()
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Byoyomi')
            ->findAll();
    }

    /**
     * @param int $seasonId
     *
     * @return int
     *
     */
    public function getMaxParticipantsInLeagueBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('count(p) as no')
            ->from('Season\Entity\Participant', 'p')
            ->where('p.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->andWhere('p.league IS NOT NULL')
            ->andWhere('p.hasAccepted  = 1')
            ->groupBy('p.league')
            ->orderBy('no', 'DESC')
            ->setMaxResults(1);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getMatchDaysBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('MatchDay');
        $qb->select('m')
            ->from('Season\Entity\MatchDay', 'm')
            ->where('m.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->orderBy('m.matchDay', 'ASC');

        return $qb->getQuery()->getResult();
    }


    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getMatchesBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Matches');
        $qb->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.league', 'l')
            ->innerJoin('l.season', 's')
            ->where('s.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->execute();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getInvitedUsersBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('Season\Entity\Participant', 'p')
            ->leftJoin('User\Entity\User', 'u', Join::WITH, 'p.user = u')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    private function getInvitedPlayerIdsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u.id')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'u = p.user')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        $result = $qb->getQuery()->getResult();

        return $this->getIdArray($result);
    }


    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAvailablePlayersBySeason($seasonId)
    {
        $notIn = $this->getInvitedPlayerIdsBySeason($seasonId);

        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->where($qb->expr()->notIn('u.id', $notIn))
            ->andWhere('u.active = true')
            ->andWhere('u.verified = true')
            ->orderBy('u.firstName', 'ASC')
            ->addOrderBy('u.lastName', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAcceptingUsersBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'p.user = u')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->andWhere('p.hasAccepted = 1')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getUnassignedParticipantsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.season', 'MySeason')
            ->where('MySeason.id = :seasonId')
            ->andWhere('p.league IS NULL')
            ->andWhere('p.hasAccepted = 1')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $userId
     * @param int $seasonId
     *
     * @return \Season\Entity\Participant
     */
    public function getParticipantByUserAndSeason($userId, $seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.season', 's')
            ->innerJoin('p.user', 'u')
            ->where('s.id = :seasonId')
            ->andWhere('u.id = :userId')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getParticipantsByLeague($leagueId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.league', 'League')
            ->where('League.id = :leagueId')
            ->setParameter('leagueId', $leagueId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @return \Season\Entity\Participant
     */
    public function getParticipantById($id)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Participant')
            ->find($id);
    }

    /**
     * @param int $userId
     * @param int $seasonId
     *
     * @return bool
     */
    public function isUserParticipatingInSeason($userId, $seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.season', 's')
            ->innerJoin('p.user', 'u')
            ->where('s.id = :seasonId')
            ->andWhere('u.id = :userId')
            ->andWhere('p.hasAccepted = true')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('userId', $userId);

        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }

    /**
     * @param array $result
     *
     * @return array
     */
    private function getIdArray(array $result) {

        $idArray = array();
        foreach ($result as $item) {
            $idArray[] = $item['id'];
        }
        if (empty($idArray)) {
            $idArray[]=0;
        }

        return array_unique($idArray);
    }

}
