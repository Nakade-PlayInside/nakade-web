<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Class NickForm
 *
 * @package User\Form
 */
class NickForm extends BaseForm
{

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_NICK));
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
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_NICK));

        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'NickForm';
    }
}
