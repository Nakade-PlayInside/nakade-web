<?php
namespace League\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use League\Entity\Season;

/**
 * Form for making a new season
 */
class SeasonForm extends AbstractForm
{
   
    protected $_tiebreaker = array( 
            'Hahn'  => 'Hahn', 
            'CUSS'  => 'CUSS', 
            'SODOS' => 'SODOS',
    );
   
    protected $_title='Bundesliga';
    protected $_number=1;
    
    /**
     * constructor 
     */        
    public function __construct()
    {
        //form name is SeasonForm
        parent::__construct();
        $this->setObject(new Season());
        $this->setHydrator(new Hydrator());
        
    } 
   
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
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
        
        //number
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'number',
                 'options' => array(
                    'label' =>  $this->translate('Number:'),
                ),     
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value'  => $this->_number,
                )
            )
        );
        
        //winpoints
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'winpoints',
                'options' => array(
                    'label' =>  $this->translate('Winning points:'),
                    'value_options' => array (
                        1 => '1', 
                        2 => '2', 
                        3 => '3'
                    )
                ),
                'attributes' => array(
                    'value' => 2,
                )
            )
        );
        
        //drawpoints
        $this->add(
            array(
                'name' => 'drawpoints',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Draw points:'),
                    'empty_option' => $this->translate('no draw'),
                    'value_options' => array (1 => '1', )
                ),
                'attributes' => array(
                    'value' => 1,
                )
            )
        );
        
        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker1',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('First tiebreaker:'),
                    'empty_option' => $this->translate('no tiebreak'),
                    'value_options' => $this->_tiebreaker
                 ),
                'attributes' => array(
                    'value' => 'Hahn',
                )
            )
        );
        
        //second tb
        $this->add(
            array(
                'name' => 'tiebreaker2',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Second tiebreaker:'),
                    'empty_option' => $this->translate('no tiebreak'),
                    'value_options' => $this->_tiebreaker
                ),    
                'attributes' => array(
                    'value' => 'SODOS',
                )
            )
        );
        
        //third tb
        $this->add(
            array(
                'name' => 'tiebreaker3',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Third tiebreaker:'),
                    'empty_option' => $this->translate('no tiebreak'),
                    'value_options' => $this->_tiebreaker
                ),
                'attributes' => array(
                    'value' => 'CUSS',
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
