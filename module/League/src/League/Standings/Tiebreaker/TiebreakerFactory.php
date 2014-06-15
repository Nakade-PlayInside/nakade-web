<?php
namespace League\Standings\Tiebreaker;

use League\Standings\StatsFactory;
use RuntimeException;

/**
 * Factory for Tiebreakers. Just provide the tiebreaker and you will receive
 * what you wanted, the tiebreaker points.
 * If you provide an unknown tiebreaker, you will receive an exception.
 *
 * @package League\Standings\Tiebreaker
 */
class TiebreakerFactory extends StatsFactory
{
    protected $tieBreaker;
    const TB_HAHN = 'hahn';
    const TB_CUSS = 'cuss';
    const TB_SODOS = 'sodos';
    const TB_SOS = 'sos';

    /**
     * @param string $typ
     *
     * @throws RuntimeException
     */
    protected function setTiebreaker($typ)
    {

        switch (strtolower($typ)) {

           case self::TB_HAHN:
               $this->tieBreaker = HahnPoints::getInstance();
               break;

           case self::TB_CUSS:
               $this->tieBreaker = CUSS::getInstance();
               break;

           case self::TB_SOS:
               $this->tieBreaker = SOS::getInstance();
               break;

           case self::TB_SODOS:
               $this->tieBreaker = SODOS::getInstance();
               break;

           default :
               throw new RuntimeException(
                   sprintf('A unknown tiebreaker was provided: %s', $typ)
               );
        }


    }

    /**
     * @return mixed
     */
    public function getTieBreaker()
    {
        return $this->tieBreaker;
    }

    /**
     * returns the name of the provided tiebreaker
     *
     * @param string $typ
     *
     * @return string
     */
    public function getName($typ)
    {
        $this->setTiebreaker($typ);
        /* @var $tiebreaker TiebreakerInterface */
        $tiebreaker = $this->getTieBreaker();

        return $tiebreaker->getName();
    }

    /**
     * @param string $typ
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getPoints($typ)
    {

        $this->setTiebreaker($typ);

        /* @var $tiebreaker \League\Standings\GameStats */
        $tiebreaker = $this->getTieBreaker();
        $allMatches = $this->getMatches();
        $tiebreaker->setMatches($allMatches);

        if (is_null($this->getPlayerId())) {
            throw new RuntimeException(
                sprintf('PlayerId has to be set. Found:null')
            );
        }

        $playerId = $this->getPlayerId();

        /* @var $stats TiebreakerInterface */
        $stats = $this->getTieBreaker();
        return $stats->getTieBreaker($playerId);
    }
}
