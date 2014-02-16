<?php

namespace Mail\Service;

use Traversable;
use Zend\Mail\Message;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class MailMessageFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        $message = new Message();
        $message->setEncoding("UTF-8");
        
        //from
        $from = isset($config['nakade_mail']['message']['from']) ?
            $config['nakade_mail']['message']['from']:null;    
       
         if($from === null )
            throw new RuntimeException(sprintf(
                'Messsage option "from" could not be found."'
            ));
        
         //optional from name
        $name = isset($config['nakade_mail']['message']['name']) ?
            $config['nakade_mail']['message']['name']:null;    
        
        //set from
        $message->setFrom($from, $name);
        
         
        //replyto
        $reply = isset($config['nakade_mail']['message']['reply']) ?
            $config['nakade_mail']['message']['reply']:null;    
        
        $message->setReplyTo($reply);
        
        return $message;
    }
}
