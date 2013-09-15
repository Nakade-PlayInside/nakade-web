<?php
namespace Message\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Message\Entity\Message;

/**
 * Form for making a new league
 */
class MessageForm extends AbstractForm
{
    private $recipients = array();
    
    /**
     * Constructor
     */        
    public function __construct($recipients)
    {
        foreach ($recipients as $object) {
            $this->recipients[$object->getId()] = $object->getShortName() ;
        }
        asort($this->recipients);
        
        //form name is LeagueForm
        parent::__construct($name='MessageForm');
        $this->setObject(new Message());
        $this->setHydrator(new Hydrator());
        $this->init();
    } 
    
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
       
        //recipient
        $this->add(
            array(
                'name' => 'receiver',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('recipient').":",
                    'empty_option' => $this->translate('Please choose'),
                     'value_options' => $this->recipients,
                ),
                
            )
        );
        
        //subject
        $this->add(
            array(
                'name' => 'subject',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('subject').':',
                ),
                'attributes' => array(
                    'value' => "titel", //$this->_title,
                    
                )
            )
        );
        
        //message
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'message',
                'options' => array(
                    'label' =>  $this->translate('message').":",
                ),
            )
        );
        
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
    
    public function getFilter()
    {
        $filter = new \Zend\InputFilter\InputFilter();
     
        $filter->add(
             array(
                 'name' => 'subject',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8', 
                                  'max'  => '200',
                           )  
                     ),  
                  )
              )
         );
        
        return $filter;
    }
    
}
?>
