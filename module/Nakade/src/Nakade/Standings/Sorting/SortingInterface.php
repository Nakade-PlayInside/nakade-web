<?php
namespace Nakade\Standings\Sorting;

/**
 * Interface SortingInterface
 *
 * @package Nakade\Statistics\Sorting
 */
Interface SortingInterface
{
    const BY_POINTS='points';
    const BY_NAME='name';
    const BY_SUSPENDED_GAMES='suspended';
    const BY_PLAYED_GAMES='played';
    const BY_WON_GAMES='win';
    const BY_LOST_GAMES='lost';
    const BY_DRAW_GAMES='draw';
    const BY_FIRST_TIEBREAK='Tb1';
    const BY_SECOND_TIEBREAK='Tb2';
    const BY_THIRD_TIEBREAK='Tb3';

}

