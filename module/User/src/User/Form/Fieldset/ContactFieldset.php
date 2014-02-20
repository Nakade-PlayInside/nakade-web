<?php
namespace User\Form\Fieldset;

use \Zend\InputFilter\InputFilterProviderInterface;
use User\Form\Fieldset\AbstractFieldset;

/**
 * Form for adding a new User
 */
class ContactFieldset extends AbstractFieldset 
        implements InputFilterProviderInterface
        
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        //form name is LeagueForm
        parent::__construct('contact');
        $this->prepare();
    } 
   
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    protected function prepare() {
       
        $this->setLabel($this->translate("Contact"));
      
        //phone
        $this->add(
            array(
                'name' => 'phone',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Phone (@home):'),
                ),
            )
        );
        
        //phone
        $this->add(
            array(
                'name' => 'otherphone',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Phone (@other):'),
                ),
            )
        );
        
         //mobile
        $this->add(
            array(
                'name' => 'mobile',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Mobile:'),
                    
                ),
            )
        );
        
        //skype
        $this->add(
            array(
                'name' => 'skype',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Skype:'),
                    
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
