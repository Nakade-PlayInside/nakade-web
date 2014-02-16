<?php
namespace User\Form\Fieldset;

use \Zend\InputFilter\InputFilterProviderInterface;
use User\Form\Fieldset\AbstractFieldset;

/**
 * Form for adding a new User
 */
class AdressFieldset extends AbstractFieldset 
        implements InputFilterProviderInterface
        
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        //form name is LeagueForm
        parent::__construct('credentials');
        $this->prepare();
    } 
   
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    protected function prepare() {
       
        $this->setLabel($this->translate("Adress"));
      
        //Street
        $this->add(
            array(
                'name' => 'street',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Street:'),
                ),
            )
        );
        
         //plz
        $this->add(
            array(
                'name' => 'city',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('PO Box:'),
                    
                ),
            )
        );
        
        //city
        $this->add(
            array(
                'name' => 'city',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('City:'),
                    
                ),
            )
        );
        
        //country
        $this->add(
            array(
                'name' => 'country',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Country:'),
                    
                ),
            )
        );
        
        
       
    } 
    
    public function getInputFilterSpecification() {
        
        return array(
            'name' => array(
                'required' => true,
            ),
            'email'=> array(
                'required' => true,
            ),
        );
    }
}
?>
