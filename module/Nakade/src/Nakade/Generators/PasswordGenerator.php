<?php
namespace Nakade\Generators;

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
    private $plainPassword;
    private $length;

    public function __construct($pwdLength=8)
    {
        $this->length = intval($pwdLength);
    }


    /**
     * generates a randomized and encrypted password
     *
     * @return string
     */
    public function generatePassword()
    {

        $letters = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+_!%&()=[]@" ;
        $password = "";
        $base = str_shuffle($letters);
        srand((double) microtime()*1000000);

        for ($i=0; $i < $this->getLength(); $i++) {

            $index = rand(0, strlen($base)-1);
            $password .= $base[$index];
        }

        return $this->encryptPassword($password);
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function encryptPassword($password)
    {
        $this->plainPassword = $password;

        //todo: password hashing and using crypt
        return md5($password);
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

}

