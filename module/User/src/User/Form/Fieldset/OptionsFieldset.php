<?php
namespace User\Form\Fieldset;

use \Zend\InputFilter\InputFilterProviderInterface;
use User\Form\Fieldset\AbstractFieldset;

/**
 * Form for adding a new User
 */
class OptionsFieldset extends AbstractFieldset 
        implements InputFilterProviderInterface
        
{
  
   
    /**
     * Constructor
     */        
    public function __construct($em)
    {
        $this->_entity_manager = $em;
        //form name is LeagueForm
        parent::__construct('options');
        $this->prepare();
    } 
   
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    protected function prepare() {
       
        $this->setLabel($this->translate("Options"));
      
        //language
        $this->add(
            array(
                'name' => 'language',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('language:'),
                ),
            )
        );
        
        
        //skype
        $this->add(
            array(
                'name' => 'twitter',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Twitter Messages:'),
                    
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
