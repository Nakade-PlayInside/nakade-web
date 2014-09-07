<?php
namespace Season\Schedule;

use Season\Entity\Match;
use Season\Services\RepositoryService;
use Season\Entity\MatchDay;
use Season\Entity\League;
/**
 * Class Schedule
 *
 * @package Season\Schedule
 */
class Schedule extends SeasonRepositoryBase
{
    private $matchDays;
    private $leaguePairingService;

    /**
     * @param RepositoryService $repositoryService
     */
    public function __construct(RepositoryService $repositoryService)
    {
        parent::__construct($repositoryService);
        $this->leaguePairingService = new HarmonicLeaguePairing();
    }

    /**
     * @param int $seasonId
     */
    public function getSchedule($seasonId)
    {
        $leagues = $this->getLeagueMapper()->getLeaguesBySeason($seasonId);
        $this->matchDays = $this->getSeasonMapper()->getMatchDaysBySeason($seasonId);

        /* @var $league \Season\Entity\League */
        foreach ($leagues as $league) {
            $players = $this->getSeasonMapper()->getParticipantsByLeague($league->getId());
            $leaguePairings = $this->getLeaguePairingService()->getPairings($players);
            $this->makeLeagueMatches($league, $leaguePairings);
        }
    }

    /**
     * @param League $league
     * @param array  $leaguePairings
     */
    private function makeLeagueMatches($league, array $leaguePairings)
    {
        foreach ($leaguePairings as $round => $pairing) {
            $matchDay = $this->getMatchDay($round);
            $this->makeMatchDayPairings($league, $matchDay, $pairing);
        }
    }

    /**
     * @param League   $league
     * @param MatchDay $matchDay
     * @param array    $matchDayPairings
     */
    private function makeMatchDayPairings($league, $matchDay, array $matchDayPairings)
    {
        $matchDate = $matchDay->getDate();

        foreach ($matchDayPairings as $pairing) {
            $match = $this->createMatch($pairing);
            $match->setMatchDay($matchDay);
            $match->setDate($matchDate);
            $match->setLeague($league);
            $this->getLeagueMapper()->save($match);
        }
    }

    /**
     * @param array $pairing
     *
     * @return Match
     */
    private function createMatch(array $pairing)
    {
        $black = array_shift($pairing)->getUser();
        $white = array_pop($pairing)->getUser();

        $match = new Match();
        $match->setBlack($black);
        $match->setWhite($white);
        $match->setSequence(0);

        return $match;
    }

    /**
     * @param int $matchDay
     *
     * @return MatchDay
     *
     * @throws \RuntimeException
     */
    private function getMatchDay($matchDay)
    {

        $round = $matchDay - 1;
        if (empty($this->matchDays) || !array_key_exists($round, $this->matchDays)) {
            throw new \RuntimeException(
                sprintf('Match day %s is not existing.', $round)
            );
        }
        return $this->matchDays[$round];
    }

    /**
     * @return \Season\Schedule\HarmonicLeaguePairing
     */
    private function getLeaguePairingService()
    {
        return $this->leaguePairingService;
    }

}
