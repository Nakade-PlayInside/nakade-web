<?php
/**
 * This is a sample "local" configuration for your application. To use it, copy
 * it to your config/autoload/ directory of your application, and edit to suit
 * your application.
  */
return array(

    'Appointment' => array(

        //your text domain for translation
        'text_domain' => 'Appointment',

        //showing max date for selecting an appointment date in weeks
        'max_date_period' => 4,

        //automatic confirmation after given time in hours
        'auto_confirm_time' => 72,

        //deadline for making appointment in a given time of hours
        'deadline_date_shift' => 72,

        //url to direct
        'url' => 'http://www.nakade.de',
    ),
);
