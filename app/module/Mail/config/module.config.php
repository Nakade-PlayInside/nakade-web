<?php
return array(
    'nakade_mail' => array(
       
        'service_manager' => array(
            'factories' => array(
                'MailMessage'   => 
                    'Mail\Service\MailMessageFactory',
                'MailTransport' => 
                    'Mail\Service\MailTransportFactory',
            
            ),
        ),
    )   
);
