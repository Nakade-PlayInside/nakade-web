<?php
namespace Authentication\Adapter;

use Nakade\Generators\PasswordGenerator as PasswordService;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Zend\Authentication\Adapter\Exception;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\I18n\Translator\Translator;

/**
 * Doctrine authentication with md5 encryption, checking verified and active
 * flag. Furthermore, date of login is saved if authenticated.
 *
 * @package Authentication\Adapter
 */
class AuthAdapter extends ObjectRepository
{

    private  $translator;
    private  $textDomain="Application";
    private  $pwdService;

    /**
     * @param PasswordService $pwdService
     * @param array $options
     */
    public function __construct(PasswordService $pwdService, $options = array())
    {
        parent::__construct($options);
        $this->pwdService = $pwdService;
    }

    /**
     * Set the credential value to be used.
     * using md5 encryption
     *
     * @param mixed $credentialValue
     *
     * @return ObjectRepository
     */
    public function setCredentialValue($credentialValue)
    {
        $this->credentialValue = $this->getPasswordService()->encryptPassword($credentialValue);
        return $this;
    }


    /**
     * Overwritten method for validating the record in the result set.
     * Additional action proves if the account is verified and active.
     *
     * @param object $identity
     *
     * @throws Exception\UnexpectedValueException
     *
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
                'Property (%s) in (%s) is not accessible.
                You should implement %s::%s()',
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

        if ($credentialValue !== true &&
                $credentialValue != $documentCredential) {
            $this->authenticationResultInfo['code'] =
                AuthenticationResult::FAILURE_CREDENTIAL_INVALID;
            $this->authenticationResultInfo['messages'][] =
                $this->translate('Supplied credential is invalid.');

            return $this->createAuthenticationResult();
        }

       //Verified Account
       if (!$identity->isVerified()) {

            $this->authenticationResultInfo['code'] =
                    AuthenticationResult::FAILURE_UNCATEGORIZED ;
            $this->authenticationResultInfo['messages'][] =
                $this->translate('Account is not verified.') . ' ' .
                $this->translate('Please check your email.');

            return $this->createAuthenticationResult();
       }

       //Active Account
       if (!$identity->isActive()) {

            $this->authenticationResultInfo['code'] =
                    AuthenticationResult::FAILURE_UNCATEGORIZED ;
            $this->authenticationResultInfo['messages'][] =
                $this->translate("Account is not active.") . ' ' .
                $this->translate("Please refer an administrator.");

            return $this->createAuthenticationResult();
       }

        //save LoginDate
        $date = new \DateTime();

        if (is_null($identity->getFirstLogin())) {
            $identity->setFirstLogin($date);
        }

        $identity->setLastLogin($date);

        //get entity and save it
        $entityManager=$this->options->getObjectManager();
        $entityManager->flush($identity);

        $this->authenticationResultInfo['code']       =
                AuthenticationResult::SUCCESS;
        $this->authenticationResultInfo['identity']   =
                $identity;
        $this->authenticationResultInfo['messages'][] =
                $this->translate('Authentication successful.');

        return $this->createAuthenticationResult();
    }

    /**
     * @param PasswordService $pwdService
     */
    public function setPwdService($pwdService)
    {
        $this->pwdService = $pwdService;
    }

    /**
     * @return PasswordService
     */
    public function getPasswordService()
    {
        return $this->pwdService;
    }



    /**
     * @param \Zend\I18n\Translator\Translator $translator
     *
     * @return ObjectRepository
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * getter
     *
     * @return \Zend\I18n\Translator\Translator $translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param string $textDomain
     *
     * @return ObjectRepository
     */
    public function setTextDomain($textDomain)
    {
        if (null !== $textDomain) {
            $this->textDomain = $textDomain;
        }
        return $this;
    }

    /**
     * getter
     *
     * @return string $textDomain
     */
    public function getTextDomain()
    {
        return $this->textDomain;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public function translate($message)
    {
        if (is_null($this->translator)) {
            return $message;
        }

        return $this->getTranslator()->translate(
            $message,
            $this->getTextDomain()
        );
    }

}
