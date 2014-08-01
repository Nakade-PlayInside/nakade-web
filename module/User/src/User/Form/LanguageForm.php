<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Class LanguageForm
 *
 * @package User\Form
 */
class LanguageForm extends BaseForm
{
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_LANGUAGE));
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
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'LanguageForm';
    }
}

