<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation;

use Nakade\Result\ResultInterface;
use Season\Entity\Match;
use Stats\Calculation\PlayerMatchStats\MatchTypeInterface;
use Stats\Calculation\PlayerMatchStats\PlayerMatchStatsFactory;
use Stats\Calculation\PlayerMatchStats\PlayerMatchStatsInterface;
use Stats\Entity\PlayerStats;

class PlayerStatsFactory
{
    private $factory;
    private $playerStats;

    public function __construct(PlayerMatchStatsFactory $factory)
    {
        $this->factory = $factory;
        $this->playerStats = new PlayerStats();
    }

    /**
     * @param array $matches
     *
     * @return $this
     */
    public function doEvaluation(array $matches)
    {
        /* @var $match \Season\Entity\Match */
        foreach ($matches as $match) {
            $this->getFactory()->addMatch($match);
        }
        $data = $this->getFactory()->getData();
        $data['matches'] = $matches;
        $this->playerStats->exchangeArray($data);

        return $this;
    }

    /**
     * @return PlayerStats
     */
    public function getPlayerStats()
    {
        return $this->playerStats;
    }

    /**
     * @return PlayerMatchStatsFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

}