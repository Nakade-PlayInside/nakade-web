<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use User\Form\Fields\Birthday;

/**
 * Form for editing the user's birthday.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class BirthdayForm extends DefaultForm
{
    private $field;

    public function getField()
    {
        if (is_null($this->field)) {
            $this->field=new Birthday($this->getTranslator(), $this->getTranslatorTextDomain());
        }
        return $this->field;
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {

        //birthday
        $this->add($this->getField()->getField());

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
        $filter->add($this->getField()->getFilter());


         return $filter;
    }
}
?>
