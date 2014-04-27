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
 * Services for controller. A mapper is set for database action.
 *
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class UserServiceFactory extends AbstractService
{
    /**
     * time in hours before expiring the verification
     *
     * @var int
     */
    protected $_expire=72;

    /**
     * Creating service for the controller.
     * Setting a mapper for database action and a mail factory for
     * sending mails.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return UserServiceFactory
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //expire verification in hours: default 72h
        $this->_expire = isset($config['User']['email_options']['expire']) ?
            $config['User']['email_options']['expire'] : 72;

        $this->_mailFactory = $services->get('User\Factory\UserMailFactory');

        $mapper = $services->get('User\Factory\MapperFactory');
        $this->setMapperFactory($mapper);


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
       //  $this->getMapper('user')->save($user);

         //send verify mail to user
         $mail = $this->getMailFactory()->getMail('verify');
         $mail->setData($data);
         $mail->setRecipient($user->getEmail(), $user->getName());

         $mail->send();

    }

    /**
     * prepraring data before filling the entity.
     * Params is given by reference..
     *
     * @param array $data
     * @throws RuntimeException
     */
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

    /**
     * edit user entity and saving.
     *
     * @param User $user
     */
    public function editUser($user)
    {
        // to fulfill the created value @deprecated
        $created = $user->getCreated();
        $data['created'] = empty($created)? new \DateTime():$created;

        $user->populate($data);
        $this->getMapper('user')->save($user);
    }

    /**
     * Activates the user by its provided string code and email.
     * This is the link send to the new user by email. By clicking
     * the link the user verifies his email adress
     *
     * @param string $email
     * @param string $verifyString
     * @return boolean
     */
    public function activateUser($email, $verifyString)
    {
        $user = $this->getMapper('user')
                           ->getActivateUser($email, $verifyString);

        if(null===$user) {
            return false;
        }

        $user->setVerified(true);
        $this->getMapper('user')->save($user);
        return true;

    }

     /**
     * User requesting a new password. If registered, password is
     * generated and all credentials are sent to the given email.
     *
     * @param array $data
     * @return boolean
     */
    public function forgotPassword($data)
    {
        $user = $this->getMapper('user')->getUserByEmail($data['email']);
        if(null === $user)
            return false;

        $data['uid'] = $user->getId();
        return $this->resetPassword($data);

    }

    /**
     * Reset the password of a user. The new generated password
     * is send to the user by email and needs to be verified.
     *
     * @param array $data
     * @throws RuntimeException
     */
    public function resetPassword($data)
    {
         $key = 'uid';
         if(!array_key_exists($key,$data)) {
             throw new RuntimeException(
                   __METHOD__ . ' expects an array key: ' . $key
             );
         }

         $user = $this->getMapper('user')->getUserById($data[$key]);

         if(null===$user) {
             throw new RuntimeException(
                   sprintf("User with id:%s not found", $data[$key])
             );
         }

         $data['pwdChange'] = new \DateTime();

         $this->prepareData($data);
         $user->populate($data);
         $this->getMapper('user')->save($user);

         $mailData = array_merge($data, $user->getArrayCopy());

        //send verify mail to user
         $mail = $this->getMailFactory()->getMail('password');
         $mail->setData($mailData);
         $mail->setRecipient($user->getEmail(), $user->getName());
         return $mail->send();

    }

    /**
     * Deactivate a user but not deleting. This is for database consistancy
     *
     * @param int $uid
     * @return boolean
     */
    public function deleteUser($uid)
    {
        $user = $this->getMapper('user')->getUserById($uid);

        if(null===$user) {
            return false;
        }

        $user->setActive(false);
        $this->getMapper('user')->save($user);
        return true;
    }

    /**
     * Activate a deactivated user.
     *
     * @param int $uid
     * @return boolean
     */
    public function undeleteUser($uid)
    {
        $user = $this->getMapper('user')->getUserById($uid);

        if(null===$user) {
            return false;
        }

        $user->setActive(true);
        $this->getMapper('user')->save($user);
        return true;
    }


}


