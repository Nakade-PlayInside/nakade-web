<?php
namespace Appointment\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Message\Entity\Message;
use \Zend\InputFilter\InputFilter;
use \Zend\I18n\Translator\Translator;

class AppointmentForm extends AbstractForm
{
    private $recipients = array();

    /**
     * @param array      $recipients
     * @param Translator $translator
     */
    public function __construct(array $recipients=null, Translator $translator = null)
    {
        foreach ($recipients as $object) {
            $this->recipients[$object->getId()] = $object->getShortName();
        }
        asort($this->recipients);

        //form name
        parent::__construct('MessageForm');

        $this->setTranslator($translator);
        $this->setTranslatorTextDomain('Message');
        $this->setObject(new Message());
        $this->setHydrator(new Hydrator());
        $this->init();
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
       // 'Y-m-d\TH:iP'

        //recipient
        $this->add(
            array(
                'name' => 'appointment-date-time',
                'type' => 'Zend\Form\Element\DateTimeLocal',
                'options' => array(
                   // 'label' =>  $this->translate('Appointment Date').":",
                    'format' => 'Y-m-d TH:iP'
                ),
                'attributes' => array(
                    'min'  => \date('Y-m-d'),
                    'max'  => '2020-01-01T00:00:00Z',
                    'step' => '1', // days; default step interval is 1 day
                )

            )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\Time',
            'name' => 'time',
            'options'=> array(
                'label' => 'Time'
            ),
            'attributes' => array(
                'min' => '00:00:00',
                'max' => '23:59:59',
                'step' => '300', // seconds; default step interval is 60 seconds
            )
        ));



        //cross-site scripting hash protection
        //this is handled by ZF2 in the background - no need for server-side
        //validation
        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )
        );

        //submit button
        $this->add(
            array(
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Submit'),

                ),
            )
        );

        //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Cancel'),

                ),
            )
        );

    }

    /**
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        return $filter;
    }

}

