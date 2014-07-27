<?php
namespace Nakade\Generators;

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
    private $length;

    public function __construct($strLength=16)
    {
        $this->length = intval($strLength);
    }

    /**
     * generates a randomized verify String.
     *
     * @return string
     */
    public function generateVerifyString()
    {

        $letters = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@";
        $verifyString = "";
        $base = str_shuffle($letters);

        for ($i = 0; $i < $this->getLength(); $i++) {
            $index = mt_rand(0, strlen($base)-1);
            $verifyString .= $base[$index];
        }

        return $verifyString;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }



}
