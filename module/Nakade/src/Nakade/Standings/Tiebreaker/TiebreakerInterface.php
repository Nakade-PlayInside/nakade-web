<?php
namespace Nakade\Standings\Tiebreaker;

/**
 * Interface TiebreakerInterface
 *
 * @package League\Standings\Tiebreaker
 */
interface TiebreakerInterface
{

    /**
     * @param int $playerId
     */
    public function getTieBreaker($playerId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();
}


