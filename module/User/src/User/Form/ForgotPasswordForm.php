<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use Zend\InputFilter\InputFilter;

/**
 * Class ForgotPasswordForm
 *
 * @package User\Form
 */
class ForgotPasswordForm extends BaseForm
{

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('PasswordForgotForm');
        $this->setFieldSetService($service);
        $this->setInputFilter($this->getFilter());
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        //email
        $this->addEmail();
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
        $filter->add($this->getField()->getFilter());

         return $filter;
    }
}

