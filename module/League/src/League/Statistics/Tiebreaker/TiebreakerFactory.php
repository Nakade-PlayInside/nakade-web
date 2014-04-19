<?php
namespace League\Statistics\Tiebreaker;

use RuntimeException;

/**
 * Factory for Tiebreakers. Just provide the tiebreaker and you will receive
 * what you wanted, the tiebreaker points.
 * If you provide an unknown tiebreaker, you will receive an exception.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class TiebreakerFactory
{

    protected $tieBreaker;
    protected $allMatches;
    protected $playerId;

    /**
     * constructor needs an array of match entities
     *
     * @param array $allMatches
     */
    public function __construct($allMatches)
    {
        $this->allMatches=$allMatches;
    }

    /**
     * set the playerId
     *
     * @param int $playerId
     *
     * @return \League\Statistics\Tiebreaker\TiebreakerFactory
     */
    public function setPlayerId($playerId)
    {
        $this->playerId=$playerId;
        return $this;
    }

    /**
     * get the playerID
     * @return int
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * using the switch.
     *
     * @param string $typ
     *
     * @throws RuntimeException
     */
    protected function setTiebreaker($typ)
    {

        switch (strtolower($typ)) {

           case "hahn"  :   $this->tieBreaker = HahnPoints::getInstance();
               break;

           case "cuss"  :   $this->tieBreaker = CUSS::getInstance();
               break;

           case "sos"   :   $this->tieBreaker = SOS::getInstance();
               break;

           case "sodos"   :   $this->tieBreaker = SODOS::getInstance();
               break;

           default :
               throw new RuntimeException(
                   sprintf('A unknown tiebreaker was provided: %s', $typ)
               );
        }


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
        return $this->tieBreaker->getName();
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
        $this->tieBreaker->setMatches($this->allMatches);

        if (is_null($this->getPlayerId())) {
            throw new RuntimeException(
                sprintf('PlayerId has to be set. Found:null')
            );
        }
        return $this->tieBreaker->getTieBreaker($this->getPlayerId());
    }
}
