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
     * Creating service for the controller.
     * Setting a mapper for database action and a mail factory for
     * sending mails.
     *
     * @param ServiceLocatorInterface $services
     *
     * @return UserServiceFactory
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //expire verification in hours: default 72h
        $this->expire = isset($config['User']['email_options']['expire']) ?
            $config['User']['email_options']['expire'] : 72;

        $mailFactory = $services->get('User\Factory\UserMailFactory');
        $this->setMailFactory($mailFactory);

        $mapper = $services->get('User\Factory\MapperFactory');
        $this->setMapperFactory($mapper);


        return $this;

    }

    /**
     * adding an user
     *
     * @param array $data
     *
     * @return bool
     */
    public function addUser(array $data)
    {
         //sets pwd, verifyStr etc
         $this->prepareData($data);

         //make new user
         $user = new User();
         $user->exchangeArray($data);
         $this->getMapper('user')->save($user);

         /* @var $mail \User\Mail\UserMail */
         $mail = $this->getMailFactory()->getMail('verify');
         $mail->setData($data);
         $mail->setRecipient($user->getEmail(), $user->getName());

         return $mail->send();

    }

    /**
     * Activates the user by its provided string code and email.
     * This is the link send to the new user by email. By clicking
     * the link the user verifies his email adress
     *
     * @param string $email
     * @param string $verifyString
     *
     * @return boolean
     */
    public function activateUser($email, $verifyString)
    {
        /* @var $user User */
        $user = $this->getMapper('user')->getActivateUser($email, $verifyString);

        if (null === $user) {
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
      *
     * @return boolean
     */
    public function forgotPassword(array $data)
    {
        /* @var $user User */
        $user = $this->getMapper('user')->getUserByEmail($data['email']);
        if (null === $user) {
            return false;
        }

        $data['uid'] = $user->getId();
        return $this->resetPassword($data);

    }

    /**
     * Reset the password of a user. The new generated password
     * is send to the user by email and needs to be verified.
     *
     * @param array $data
     *
     * @throws RuntimeException
     *
     * @return true;
     */
    public function resetPassword(array $data)
    {
         $key = 'uid';
         if (!array_key_exists($key, $data)) {
             throw new RuntimeException(
                 __METHOD__ . ' expects an array key: ' . $key
             );
         }

        /* @var $user User */
         $user = $this->getMapper('user')->getUserById($data[$key]);

         if (null === $user) {
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
        /* @var $mail \User\Mail\UserMail */
         $mail = $this->getMailFactory()->getMail('password');
         $mail->setData($mailData);
         $mail->setRecipient($user->getEmail(), $user->getName());
         $mail->send();
         return true;

    }


}


