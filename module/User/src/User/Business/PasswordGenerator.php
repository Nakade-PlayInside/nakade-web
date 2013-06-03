<?php
namespace User\Business;

/**
 * Generator for randomized passwords. This passwords are not intended
 * for infinite use. Due to the randomization method, the generated 
 * password is not safe by all means.
 * But it can be used for emailing this password and requesting a 
 * new password as response to the email.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PasswordGenerator {
    
    private static $instance =null;
    protected $_letters = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+_!%&()=[]@" ;
    
    
   /**
    * Singleton Pattern for preventing object inflation.
    * @return AbstractGameStats
    */
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new PasswordGenerator();
        }
        
        return self::$instance;
    }      
    
    /**
     * generates a randomized password with 8 chars in length by default. 
     * 
     * @param int $length optional password length
     * @return string random password
     */
    public function generatePassword($length=8)
    {
       
        $password = "";
        $base = str_shuffle($this->_letters);
        srand((double)microtime()*1000000);
        
        for ($i = 0; $i < $length; $i++) {
            
            $index = rand(0, strlen($this->_letters)-1);
            $password .= $base[$index];
        }
        
        return $password;
    }
            
}

?>
