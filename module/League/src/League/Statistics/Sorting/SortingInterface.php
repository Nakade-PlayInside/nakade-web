<?php
namespace League\Statistics\Sorting;

/**
 * Interface SortingInterface
 *
 * @package League\Statistics\Sorting
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
    const BY_FIRST_TIEBREAK='firstTiebreak';
    const BY_SECOND_TIEBREAK='secondTiebreak';
    const BY_THIRD_TIEBREAK='thirdTiebreak';

}

