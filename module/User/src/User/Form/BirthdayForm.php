<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\BirthdayHydrator;
use Zend\InputFilter\InputFilter;

/**
 * Class BirthdayForm
 *
 * @package User\Form
 */
class BirthdayForm extends BaseForm
{

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('BirthdayForm');

        $this->setFieldSetService($service);
        $hydrator = new BirthdayHydrator();
        $this->setHydrator($hydrator);
        $this->setInputFilter($this->getFilter());
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->addBirthday();
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
        $filter->add($this->getUserFilter(self::FILTER_BIRTHDAY));
        return $filter;
    }
}
