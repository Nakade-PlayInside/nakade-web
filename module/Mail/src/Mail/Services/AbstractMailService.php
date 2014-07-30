<?php

namespace Mail\Services;

use Nakade\Services\NakadeTranslationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractMailService
 *
 * @package Mail\Services
 */
abstract class AbstractMailService extends NakadeTranslationService implements MailServiceInterface
{
    protected $transport;
    protected $message;
    protected $signature;

    /**
     * @param string $typ
     *
     * @return \User\Mail\UserMail
     *
     * @throws \RuntimeException
     */
    abstract public function getMail($typ);


    /**
     * @return \Mail\Services\MailMessageFactory
     *
     * @throws \RuntimeException
     */
    public function getMessage()
    {
        $this->message =   $this->getServices()->get('Mail\Services\MailMessageFactory');
        if (is_null($this->message)) {
            throw new \RuntimeException(
                sprintf('Mail Message Service is not found.')
            );
        }
        return $this->message;
    }

    /**
     * @return \Mail\Services\MailSignatureService
     *
     * @throws \RuntimeException
     */
    public function getSignature()
    {
        if (is_null($this->signature)) {
            $this->signature = $this->getServices()->get('Mail\Services\MailSignatureService');
            if (is_null($this->signature)) {
                throw new \RuntimeException(
                    sprintf('Mail Signature Service is not found.')
                );
            }
        }
        return $this->signature;
    }

    /**
     * @return \Mail\Services\MailTransportFactory
     *
     * @throws \RuntimeException
     */
    public function getTransport()
    {
        $this->transport = $this->getServices()->get('Mail\Services\MailTransportFactory');
        if (is_null($this->transport)) {
            throw new \RuntimeException(
                sprintf('Mail Transport Service is not found.')
            );
        }

        return $this->transport;
    }

}
