<?php

namespace Mail\Service;

use Traversable;
use Zend\Mail\Transport;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class MailTransportFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
         //default sendmail
        $method = isset($config['nakade_mail']['transport']['method']) ?
            $config['nakade_mail']['transport']['method']: 
            'sendmail' ;
        
        
        $options = isset($config['nakade_mail']['transport']['options']) ?
            $config['nakade_mail']['transport']['options']: 
            null ;
        
        //options required if not sendmail
        if('sendmail' != strtolower($method) && $options === null )
            throw new RuntimeException(sprintf(
                'Options for mail transport "%s" could not be found.',
                $method
            ));
            
   

        switch (strtolower($method)) {
            
            case 'sendmail';
                $transport = new Transport\Sendmail();
                break;
            case 'smtp';
                $options = new Transport\SmtpOptions($options);
                $transport = new Transport\Smtp($options);
                break;
            case 'file';
                $options = new Transport\FileOptions($options);
                $transport = new Transport\File($options);
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
