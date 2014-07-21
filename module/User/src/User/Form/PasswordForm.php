<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Class PasswordForm
 *
 * @package User\Form
 */
class PasswordForm extends BaseForm
{

    /**
     * init the form.
     */
    public function init()
    {

        $this->add($this->getUserFieldFactory()->getField(self::FIELD_PWD));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_PWD_REPEAT));

        $this->add($this->getButtonFieldSet());

    }

    /**
     * get the InputFilter
     *
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_PWD));
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'PasswordForm';
    }
}

