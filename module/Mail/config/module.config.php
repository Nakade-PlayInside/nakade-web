<?php
namespace Mail;

return array(

    'service_manager' => array(
        'factories' => array(
            'Mail\Services\MailMessageFactory'   =>
                'Mail\Services\MailMessageFactory',

            'Mail\Services\MailTransportFactory'  =>
                'Mail\Services\MailTransportFactory',

            'Mail\Services\MailSignatureService'  =>
                'Mail\Services\MailSignatureService',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Mail',
            ),
        ),
    ),
);
