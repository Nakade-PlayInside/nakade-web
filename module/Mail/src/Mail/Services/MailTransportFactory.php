<?php

namespace Mail\Services;

use Traversable;
use Zend\Mail\Transport\File as Download;
use Zend\Mail\Transport;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;

/**
 * Class MailTransportFactory
 *
 * @package Mail\Services
 */
class MailTransportFactory implements FactoryInterface
{
    const SENDMAIL ='sendmail';
    const SMTP ='smtp';
    const FILE ='file';

    private $options;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\Mail\Transport\TransportInterface
     *
     * @throws RuntimeException
     *
     * @throws \DomainException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        if (!isset($config['nakade_mail']['transport'])) {
            throw new RuntimeException(
                'Mail transport configuration not found.');
        }

        $tpConfig = $config['nakade_mail']['transport'];

        if (!isset($tpConfig['method'])) {
            throw new RuntimeException(
                'Mail transport method configuration not found.');
        }

        $method = $tpConfig['method'];

        //options required if not sendmail
        if (self::SENDMAIL != strtolower($method) && !isset($tpConfig['options'])) {
            throw new RuntimeException(sprintf(
                'Options for mail transport "%s" could not be found.',
                $method
            ));
        }

        $this->setOptions($tpConfig['options']);
        return $this->getTransport($method);

    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $method
     *
     * @return \Zend\Mail\Transport\TransportInterface
     *
     * @throws \DomainException
     */
    public function getTransport($method)
    {

        switch (strtolower($method)) {

            case self::SENDMAIL:
                $transport = new Transport\Sendmail();
                break;
            case self::SMTP:
                $options = new Transport\SmtpOptions($this->getOptions());
                $transport = new Transport\Smtp($options);
                break;
            case self::FILE:
                $options   = new Transport\FileOptions($this->getOptions());
                $transport = new Download($options);
                break;
            default:
                throw new \DomainException(
                    sprintf(
                        'Unknown mail transport type provided ("%s")', $method
                    )
                );
        }

        return $transport;
    }

}