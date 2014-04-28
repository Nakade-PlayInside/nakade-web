<?php
namespace User\Form;

use User\Form\Fields\ForgotPassword;
use Zend\InputFilter\InputFilter;

/**
 * Form for changing email adress.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class ForgotPasswordForm extends DefaultForm
{

    private $field;

    /**
     * @return ForgotPassword
     */
    public function getField()
    {
        if (is_null($this->field)) {
            $this->field=new ForgotPassword($this->getTranslator(), $this->getTranslatorTextDomain());
        }
        return $this->field;
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        //email
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

