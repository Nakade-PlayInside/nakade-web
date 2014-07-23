<?php
namespace Authentication\Password;

/**
 * Generator for randomized passwords. This passwords are not intended
 * for infinite use. Due to the randomization method, the generated
 * password is not safe by all means.
 * But it can be used for emailing this password and requesting a
 * new password as response to the email.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PasswordGenerator
{

    /**
     * generates a randomized password with 8 chars in length by default.
     *
     * @param int $length optional password length
     *
     * @return string random password
     */
    public static function generatePassword($length=8)
    {

        $letters = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+_!%&()=[]@" ;
        $password = "";
        $base = str_shuffle($letters);
        srand((double) microtime()*1000000);

        for ($i=0; $i<$length; $i++) {

            $index = rand(0, strlen($base)-1);
            $password .= $base[$index];
        }

        return $password;
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public static function encryptPassword($password)
    {
        //todo: password hashing and using crypt
        return md5($password);
    }
}

