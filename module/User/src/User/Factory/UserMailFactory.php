<?php

namespace User\Factory;

use Traversable;
use User\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Creates a translated mail for sending verification mails.
 * Use the fabric method for getting the mailclass required.
 */
class UserMailFactory implements FactoryInterface
{

    protected $emailOptions;
    protected $transport;
    protected $message;
    protected $translator;
    protected $textDomain;

    /**
     * implemented ServiceLocator
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $this->textDomain = isset($config['User']['text_domain']) ?
            $config['User']['text_domain'] : null;

        //email
        $this->emailOptions = isset($config['User']['email_options']) ?
            $config['User']['email_options'] : null;

        //optional translator
        $this->translator = $services->get('translator');

        //mandatory
        $this->transport = $services->get('Mail\Services\MailTransportFactory');
        $this->message   = $services->get('Mail\Services\MailMessageFactory');

        return $this;
    }

    /**
     * fabric method for getting the mail needed. expecting the mail name as
     * string. Throws an exception if provided typ is unknown.
     * Typ: - 'verify'   => new users
     *      - 'password' => reset password
     *
     * @param string $typ
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {

        switch (strtolower($typ)) {

           case "verify":   $mail = new Mail\VerifyMail($this->emailOptions);
               break;

           case "password": $mail = new Mail\PasswordMail($this->emailOptions);
               break;


           default:
               throw new \RuntimeException(
                   sprintf('An unknown mail type was provided.')
               );
        }

        $mail->setMessage($this->message);
        $mail->setTransport($this->transport);
        $mail->setTranslator($this->translator, $this->textDomain);

        return $mail;
    }

}
