<?php
namespace League\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use League\Entity\League;

/**
 * Form for making a new league
 */
class LeagueForm extends AbstractForm
{
   
    protected $_sid;
    protected $_title='Top-Liga';
    protected $_number=1;
    
    /**
     * Constructor
     */        
    public function __construct()
    {
        //form name is LeagueForm
        parent::__construct($name='LeagueForm');
        $this->setObject(new League());
        $this->setHydrator(new Hydrator());
    } 
    
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
        //number
        $this->add(
            array(
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('League No:'),
                ),
                'attributes' => array(
                    'value' => $this->_number,
                    'readonly'=> true
                )
            )
        );
        
        //title
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Title:'),
                ),
                'attributes' => array(
                    'value' => $this->_title,
                    
                )
            )
        );
        
        //season ID
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'sid',
                'options' => array(
                    'label' =>  $this->translate('SeasonId:'),
                ),
                'attributes' => array(
                    'value'  => $this->_sid,
                    'readonly'=> true
                )
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
                 'name' => 'title',
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
                                  'max'  => '20',
                           )  
                     ),  
                  )
              )
         );
        
        return $filter;
    }
    
}
?>
