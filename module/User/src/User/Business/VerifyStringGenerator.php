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
class VerifyStringGenerator {
    
    private static $instance =null;
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new VerifyStringGenerator();
        }
        
        return self::$instance;
    }      
    
    /**
     * generates a randomized password with 8 chars in length by default. 
     * 
     * @param int $length optional password length
     * @return string random password
     */
    public function generateVerifyString($length=16)
    {
       
        $verify_string = "";
        for ($i = 0; $i < $length; $i++) {
            $verify_string .= chr(mt_rand(36,126));
        }
        
        return $verify_string;
    }
            
}

?>
