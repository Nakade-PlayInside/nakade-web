<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;

/**
 * Class BirthdayForm
 *
 * @package User\Form
 */
class BirthdayForm extends BaseForm
{

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_BIRTHDAY));
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
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_BIRTHDAY));
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'BirthdayForm';
    }
}
