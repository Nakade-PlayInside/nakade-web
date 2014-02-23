<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use User\Entity\User;

/**
 * Abstract User Form with default Fields for CSRF and submit buttons
 */
abstract class DefaultForm extends AbstractForm
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        parent::__construct();
        $this->setObject(new User());
        $this->setHydrator(new Hydrator());
        
    } 
   
    public function setDefaultFields()
    {
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
                'name' => 'send',
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

    protected  function getTextField($name, $label)
    {
        return array(
            'name' => $name,
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' =>  $this->translate($label),
            ),
        );
    }

    protected function getUniqueDbFilter($name,$min,$max,$required=true)
    {
        return array(
            'name' => $name,
            'required' => $required,
            'filters' => $this->getStripFilter(),
            'validators'=> $this->getValidators($name,$min,$max)

        );
    }

    private function getValidators($name,$min,$max)
    {
        $validators=array();
        $validators[]=$this->getStringLengthConfig($min,$max);
        if(strtolower($name)=='email') {
            $validators[]=$this->getEmailValidation();
        }

        $validators[]=$this->getDBNoRecordExistConfig($name);

        return $validators;

    }

    private function getEmailValidation()
    {
       return array(
            'name' => 'EmailAddress',
            'break_chain_on_failure' => true,
        );

    }

    protected function getDBNoRecordExistConfig($property)
    {
        return array(
            'name'     => 'User\Form\Validator\DBNoRecordExist',
            'options' => array(
                'entity'   => 'User\Entity\User',
                'property' => $property,
                'exclude'  => $this->getIdentifierValue(),
                'adapter'  => $this->getEntityManager(),
            )
        );
    }

    protected function getStripFilter()
    {
       return array(
            array('name' => 'StripTags'),
            array('name' => 'StringTrim'),
            array('name' => 'StripNewLines'),
        );
    }

    protected function getStringLengthConfig($min, $max)
    {

        return array('name' => 'StringLength',
            'options' => $this->getStringLengthOptions($min,$max)
        );
    }

    private function getStringLengthOptions($min,$max)
    {
        $options = array ('encoding' => 'UTF-8');


        if(!is_null($min)){
            $options['min']=$min;
        }

        if(!is_null($max)){
            $options['max']=$max;
        }

        return $options;
    }

}
?>
