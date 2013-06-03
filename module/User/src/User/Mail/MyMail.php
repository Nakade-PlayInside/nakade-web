<?php
namespace User\Mail;

use Zend\Mail\Transport\Smtp as Transport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
/**
 * Description of Mail
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MyMail {
   
    public function __construct() {
        
        
            $tmp = array(
                'host'             => 'smtp.gmail.com',
                'port'             => 587,
                'connectionClass'  => 'login',
                'connectionConfig' => array(
                    'ssl'      => 'tls',
                    'username' => 'grrompf@gmail.com',
                    'password' => 'kMdCS2013',
                ),
            );
            
            $options   = new SmtpOptions($tmp);
            $transport = new Transport();
            
            $transport->setOptions($options);
            
            $message   = new Message(); 
            $message->setEncoding("UTF-8");
            
            $message->addFrom("holger@gmx.de", "Nakade Test Mail");
            $message->addTo("holger@nakade.de", "Holger Maerz");
            $message->addReplyTo("noreply@nakade.de");
            $message->setSubject("Sending an email");
            
            $message->setBody("This is the message body.");
            
            
            $transport->send($message);
            
        
    }
}

?>
