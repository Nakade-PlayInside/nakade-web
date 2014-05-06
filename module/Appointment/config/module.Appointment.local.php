<?php
/**
 * This is a sample "local" configuration for your application. To use it, copy
 * it to your config/autoload/ directory of your application, and edit to suit
 * your application.
  */
return array(

    'League' => array(

        //your text domain for translation
        'text_domain' => 'Appointment',

        //showing max date for selecting an appointment date in weeks
        'max_date_period' => 4,

        //automatic confirmation after given time in hours
        'auto_confirm_time' => 72,

    ),
);
