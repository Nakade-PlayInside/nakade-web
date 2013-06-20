<?php
namespace User\Services;

use Nakade\Abstracts\AbstractService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;

use User\Business\PasswordGenerator;
use User\Business\VerifyStringGenerator;
use User\Entity\User;

/**
 * Factory for creating the Zend Authentication Service. Using customized
 * Adapter and Storage instances. 
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ProfileServiceFactory extends AbstractService
{
   
    /**
     * Creating Zend Authentication Service for logIn and logOut action.
     * Making use of customized adapters for more action as by default.
     * Integration of optional translation feature (i18N)
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Zend\Authentication\AuthenticationService
     * @throws RuntimeException
     * 
     */
    public function createService(ServiceLocatorInterface $services)
    {
      
        $mapper = $services->get('User\Factory\MapperFactory');
        $this->_mapper = $mapper->getMapper('user');
        $this->_form = $services->get('user_form');
       
        
        return $this;
        
    }
    
    /**
     * adding an user 
     * 
     * @param Request $request
     * @param array $data
     */
    public function addUser($data)
    {
         //sets pwd, verifyStr etc
         $this->prepareData($data); 
        
         //make new user
         $user = new User();
         $user->exchangeArray($data);
         $this->getMapper()->save($user);
     
         //send verify mail to user
         $mail = $this->getMailFactory()->getMail('verify');
         $mail->setData($data);
         $mail->setRecipient($user->getEmail(), $user->getName());
         $mail->send();
        
    }
    
    private  function prepareData(&$data)
    {
         $key = 'request';
         if(!array_key_exists($key,$data)) {
             throw new RuntimeException(
                   __METHOD__ . ' expects an array key: ' . $key
             );
         }    
                 
         $request = $data[$key]; 
         $uri       = $request->getUri();
         $verifyUrl = sprintf('%s://%s',$uri->getScheme(), $uri->getHost());
         
         $data['verifyString'] = VerifyStringGenerator::generateVerifyString();
         $data['verifyUrl']    = $verifyUrl;
        
         $password             = PasswordGenerator::generatePassword(12);
         $data['generated']    = $password;
         $data['password']     = md5($password);
         
         //expire verification 
         $now  = new \DateTime();
         $duetime  = sprintf('+ %s hour', $this->_expire);
         $data['due']    = $now->modify($duetime);
         
         $data['verified'] = 0;
    }
    
    public function editUser($user)
    {
        // to fulfill the created value @deprecated
        $created = $user->getCreated();
        $data['created'] = empty($created)? new \DateTime():$created;
        
        $user->populate($data);  
        $this->getMapper()->save($user);
    }
    
    public function activateUser($email, $verifyString) 
    {
        $user = $this->getMapper()
                           ->getActivateUser($email, $verifyString); 
        
        if(null===$user) {
            return false;
        }
        
        $user->setVerified(true);
        $this->getMapper()->save($user);
        return true;
        
    }
    
    public function resetPassword($data) 
    {
         $key = 'uid';
         if(!array_key_exists($key,$data)) {
             throw new RuntimeException(
                   __METHOD__ . ' expects an array key: ' . $key
             );
         }    
        
         $user = $this->getMapper()->getUserById($data[$key]); 
         
         if(null===$user) {
             throw new RuntimeException(
                   sprintf("User with id:%s not found", $data[$key])
             );
         }
        
         $this->prepareData($data);
         $user->populate($data);
         $this->getMapper()->save($user);
       
         $mailData = array_merge($data, $user->getArrayCopy());
         
        //send verify mail to user
         $mail = $this->getMailFactory()->getMail('password');
         $mail->setData($mailData);
         $mail->setRecipient($user->getEmail(), $user->getName());
         $mail->send();
        
    }
    
    public function deleteUser($uid) 
    {
        $user = $this->getMapper()->getUserById($uid); 
        
        if(null===$user) {
            return false;
        }
        
        $user->setActive(false);
        $this->getMapper()->save($user);
        return true;
    }
    
    public function undeleteUser($uid) 
    {
        $user = $this->getMapper()->getUserById($uid); 
        
        if(null===$user) {
            return false;
        }
        
        $user->setActive(true);
        $this->getMapper()->save($user);
        return true;
    }
    
    public function getProfile($uid) 
    {
        $user = $this->getMapper()->getUserById($uid); 
        return $user;
    }
    
}


