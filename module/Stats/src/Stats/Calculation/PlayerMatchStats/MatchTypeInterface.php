<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation\PlayerMatchStats;

Interface MatchTypeInterface
{

    const MATCH_WIN = 'win';
    const MATCH_DEFEAT = 'defeat';
    const MATCH_DRAW = 'draw';
    const MATCH_PLAYED = 'played';
    const MATCH_CONSECUTIVE_WIN = 'consecutiveWins';
}