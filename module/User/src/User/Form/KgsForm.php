<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Form for changing email adress.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class KgsForm extends DefaultForm
{
  
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
        //email
        $this->add(
            $this->getTextField('kgs','KGS (opt.):')
        );
        
        $this->setDefaultFields();
       
    } 
    
    
    
     
    /**
     * get the InputFilter
     * 
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        $filter->add($this->getUniqueDbFilter('kgs', null,'50'));


        return $filter;
    }
}
?>
