<?php
//module/Authentication/src/Authentication/Services/AuthAdapter.php
namespace Authentication\Services;

use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Zend\Authentication\Adapter\Exception;
use Zend\Authentication\Result as AuthenticationResult;

/**
 * Doctrine authentication with md5 encryption, checking verified and active
 * flag. Furthermore, date of login is saved if authenticated.
 * 
 * @author Dr. Holger Maerz
 */
class AuthAdapter extends ObjectRepository {
    
    /**
     * Set the credential value to be used.
     * using md5 encryption
     *
     * @param  mixed $credentialValue
     * @return ObjectRepository
     */
    public function setCredentialValue($credentialValue)
    {
        $this->credentialValue = md5($credentialValue);
        return $this;
    }
    
    
    /**
     * This method attempts to validate that the record in the resultset 
     * is indeed a record that matched the identity provided to this adapter.
     * In addition, validation proves if the account is verified and active. 
     *
     * @param  object $identity
     * @throws Exception\UnexpectedValueException
     * @return AuthenticationResult
     */
    protected function validateIdentity($identity)
    {
      
        $credentialProperty = $this->options->getCredentialProperty();
        $getter = 'get' . ucfirst($credentialProperty);
        $documentCredential = null;

        if (method_exists($identity, $getter)) {
            $documentCredential = $identity->$getter();
        } elseif (property_exists($identity, $credentialProperty)) {
            $documentCredential = $identity->{$credentialProperty};
        } else {
            throw new Exception\UnexpectedValueException(sprintf(
                'Property (%s) in (%s) is not accessible. You should implement %s::%s()',
                $credentialProperty,
                get_class($identity),
                get_class($identity),
                $getter
            ));
        }

        $credentialValue = $this->credentialValue;
        $callable = $this->options->getCredentialCallable();

        if ($callable) {
            $credentialValue = call_user_func($callable, $identity, $credentialValue);
        }

        if ($credentialValue !== true && $credentialValue != $documentCredential) {
            $this->authenticationResultInfo['code'] = AuthenticationResult::FAILURE_CREDENTIAL_INVALID;
            $this->authenticationResultInfo['messages'][] = 'Supplied credential is invalid.';

            return $this->createAuthenticationResult();
        }
        
       //Verified Account
       if(!$identity->isVerified()) {
           
            $this->authenticationResultInfo['code'] = 
                    AuthenticationResult::FAILURE_UNCATEGORIZED ;
            $this->authenticationResultInfo['messages'][] = 
                    'Account is not verified. '.
                    'Please check your email.';
            
            return $this->createAuthenticationResult();
        }
        
       //Active Account
       if(!$identity->isActive()) {
           
            $this->authenticationResultInfo['code'] = 
                    AuthenticationResult::FAILURE_UNCATEGORIZED ;
            $this->authenticationResultInfo['messages'][] = 
                     "Account is not active. " .
                     "Please refer an administrator.";
            
            return $this->createAuthenticationResult();
        }

        //save LoginDate
        $date = new \DateTime();

        if(is_null($identity->getFirstLogin()))
            $identity->setFirstLogin($date);
        
        $identity->setLastLogin($date);
        
        //get entitity and save it
        $entityManager=$this->options->getObjectManager();
        $entityManager->flush($identity);
        
        
        $this->authenticationResultInfo['code']       = AuthenticationResult::SUCCESS;
        $this->authenticationResultInfo['identity']   = $identity;
        $this->authenticationResultInfo['messages'][] = 'Authentication successful.';

        return $this->createAuthenticationResult();
    }

    
    
}
