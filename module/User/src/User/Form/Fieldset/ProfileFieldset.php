<?php
namespace User\Form\Fieldset;

use \Zend\InputFilter\InputFilterProviderInterface;
use User\Form\Fieldset\AbstractFieldset;

/**
 * Form for adding a new User
 */
class ProfileFieldset extends AbstractFieldset 
        implements InputFilterProviderInterface
        
{
    /**
     * Constructor
     */        
    public function __construct($em)
    {

        $this->_entity_manager = $em;
        
        parent::__construct('profile');
        $this->prepare();
    } 
   
    
    public function getEntityManager()
    {
        return $this->_entity_manager;
    }
    
    public function setEntityManager($em)
    {
        $this->_entity_manager = $em;
        return $this;
    }
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    protected function prepare() {
       
        $this->setLabel($this->translate("Profile"));
      
        $this->add(
            array(
                'name' => 'sex',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Salutation:'),
                    'value_options' => array(
                        'm' => $this->translate('Herr'), 
                        'f' => $this->translate('Frau'),
                    )
                ),
            )
        );
        
        //Title
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Title (opt.):'),
                ),
            )
        );
        
        //first name
        $this->add(
            array(
                'name' => 'firstname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('First Name:'),
                ),
            )
        );
        
        //family name
        $this->add(
            array(
                'name' => 'lastname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Family Name:'),
                ),
            )
        );
        
        //nick name
        $this->add(
            array(
                'name' => 'nickName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Nick (opt.):'),
                ),
            )
        );
        
        //anonym
        $this->add(
            array(
                'name' => 'anonym',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('use nick always (anonymizer):'),
                    'checked_value' => true,
                ),
            )
        );
        
         //birthday
        $this->add(
            array(
                'name' => 'dateOfBirth',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('Birthday (opt.):'),
                    'format' => 'Y-m-d',
                 ),
                'attributes' => array(
                     'min' => '1900-01-01',
                     'max' => date('Y-m-d'), 
                     'step' => '1',
                )
            )
        );
       
    } 
    
    public function getInputFilterSpecification() {
        
        return array(
            'title' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'max'  => '10',
                          )  
                    ),
                )   
            ),
            'lastname' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'max'  => '30',
                          )  
                          
                    ),
                )
            ),
            'firstname' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'max'  => '20',
                          )  
                    ),
                )    
            ),
            'nickName' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'max'  => '20',
                          )  
                         
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array( 
                            'entity'    => 'User\Entity\User',
                            'property' => '_nickname',
                            'adapter'  => $this->getEntityManager(),
                        )
                    )
                 )   
            ),
            'dateOfBirth' => array(
                'required' => false,
            ),
        );
    }
}
?>
