<?php
namespace User\Form;

/**
 * Form for adding a new User
 */
class UserForm extends AbstractForm
{
  
    protected $_fieldsets;
    /**
     * Constructor
     */        
    public function __construct($fieldsets)
    {
        
        $this->_fieldsets=$fieldsets;
        
        //form name is LeagueForm
        parent::__construct($name='UserForm');
        $this->init();
    } 
   
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
       // $this->setAttribute('autocomplete', 'off');
        //profile
        
        foreach($this->_fieldsets as $fieldset)
          $this->add($fieldset);
        
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
        
    } 
    
}
?>
