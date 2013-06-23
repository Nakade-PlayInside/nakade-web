<?php
namespace User\Services;

use Nakade\Abstracts\AbstractService;
use Zend\ServiceManager\ServiceLocatorInterface;



/**
 * Service for controller. A mapper is set for database action.
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ProfileServiceFactory extends AbstractService
{
   
    /**
     * Creating service for the controller. 
     * Setting a mapper for database action.
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return ProfileServiceFactory
     * 
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $mapper = $services->get('User\Factory\MapperFactory');
        $this->_mapper = $mapper->getMapper('user');
        
        return $this;
        
    }
    
    /**
     * User edit action. Getting user by id, populating the edited
     * data, while the date of edit is set. 
     * Returns boolean.
     * 
     * @param array $data
     * @return boolean
     */
    public function editProfile($data)
    {
        $id = $data['uid'];
        $data['edit'] = new \DateTime();
        
        $user = $this->getMapper()->getUserById($id);
        if(null === $user)
            return false;
        
        $user->populate($data);
        return $this->getMapper()->save($user);
    }
    
    /**
     * User password change. Setting date of change and
     * md5 encrytion of the password before saving.
     * Forwared to editProfile method.
     * 
     * @param array $data
     * @return boolean
     */
    public function editPassword($data)
    {
        $data['pwdChange'] = new \DateTime();
        $data['password'] = md5($data['password']);
        
        return $this->editProfile($data);
        
    }
    
   
    
    
    
}


