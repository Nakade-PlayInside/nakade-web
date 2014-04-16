<?php
namespace Mail;

return array(

    'service_manager' => array(
        'factories' => array(
            'Mail\Services\MailMessageFactory'   =>
                'Mail\Services\MailMessageFactory',

            'Mail\Services\MailTransportFactory'  =>
                'Mail\Services\MailTransportFactory',
        ),
    ),

);
