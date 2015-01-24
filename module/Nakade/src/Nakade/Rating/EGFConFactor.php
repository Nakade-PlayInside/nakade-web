<?php

namespace Nakade\Rating;

/**
 * The con factor is dependent on a player's rating and is used for calculating a new rating after a match.
 * For ratings exceeding 2700 usually a linear regression is used but not in this simplified algorithm.
 * see http://www.europeangodatabase.eu/EGD/EGF_rating_system.php for more details.
 * ToDo:Instead of using the mathematical average, developing a general formula is more welcomed. Determination by
 * linear regression using pairs of values and a 3rd grade polynomial term (see link below) shows correct values
 * but does not fit.
 * see http://www.arndt-bruenner.de/mathe/scripts/regr.htm
 *
 * @package Nakade\Rating
 */
class EGFConFactor
{
    const CON_MAX = 10;
    const CON_MIN = 116;

    /**
     *
     * @param int $rating
     *
     * @return int
     */
    public function getCon($rating)
    {
        /*todo: determine factor by linear regression using pairs of values and polynomial term of 3rd grade
          see http://www.arndt-bruenner.de/mathe/scripts/regr.htm, unfortunately it does not fit as proposed
          on the site
        */

        $lowerCon = $this->getNormalizedCon($rating);

        $upperRating = ceil($rating/100) *100;
        $upperCon = $this->getNormalizedCon($upperRating);
        $delta = $upperCon-$lowerCon;

        return intval($lowerCon + $delta * ($upperRating - $rating)/100);

    }

    /**
     * @param int $rating
     *
     * @return int
     */
    private function getNormalizedCon($rating)
    {
        // formula is determined by linear regression using pairs of values and polynomial term of 3rd grade
        // see more http://www.arndt-bruenner.de/mathe/scripts/regr.htm
        $normalizedRating = intval($rating/100) *100;


        switch ($normalizedRating) {
            case 100: $con=116;
                break;
            case 200: $con=110;
                break;
            case 300: $con=105;
                break;
            case 400: $con=100;
                break;
            case 500: $con=95;
                break;
            case 600: $con=90;
                break;
            case 700: $con=85;
                break;
            case 800: $con=80;
                break;
            case 900: $con=75;
                break;
            case 1000: $con=70;
                break;
            case 1100: $con=65;
                break;
            case 1200: $con=60;
                break;
            case 1300: $con=55;
                break;
            case 1400: $con=51;
                break;
            case 1500: $con=47;
                break;
            case 1600: $con=43;
                break;
            case 1700: $con=39;
                break;
            case 1800: $con=35;
                break;
            case 1900: $con=31;
                break;
            case 2000: $con=27;
                break;
            case 2100: $con=24;
                break;
            case 2200: $con=21;
                break;
            case 2300: $con=18;
                break;
            case 2400: $con=15;
                break;
            case 2500: $con=13;
                break;
            case 2600: $con=11;
                break;
            case 2700: $con=10;
                break;

            default:
                $con = 10;

                if ($rating > 2700) {
                    $con=self::CON_MAX;
                } elseif ($rating < 100) {
                    $con=self::CON_MIN;
                }

        }
        return $con;
    }

}
