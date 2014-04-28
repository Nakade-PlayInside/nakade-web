<?php
namespace User\Business;

/**
 * Generator for randomized verification strings used for email validation.
 * If passed this string during a certain time, the email is validated.
 * Similar process is used for resetting the password and sending a new
 * generated password by email.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class VerifyStringGenerator
{

    /**
     * generates a randomized verify String.
     *
     * @param int $length optional string length
     *
     * @return string random verify string
     */
    public static function generateVerifyString($length=16)
    {

        $letters = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@";
        $verifyString = "";
        $base = str_shuffle($letters);

        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, strlen($base)-1);
            $verifyString .= $base[$index];
        }

        return $verifyString;
    }

}
