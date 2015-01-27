<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation\PlayerMatchStats;

/**
 * Class ConsecutiveWins
 *
 * @package Stats\Calculation\PlayerMatchStats
 */
class ConsecutiveWins extends AbstractPlayerMatchStats
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Consecutive Wins';
    }

}