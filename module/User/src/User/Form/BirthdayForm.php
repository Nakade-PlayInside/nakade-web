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
     * @param \User\Entity\User $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        //birthday
        $this->add(array(
            'name' => 'birthday',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' =>  $this->translate('Birthday:'),
                'format' => 'Y-m-d',
                ),
            'attributes' => array(
                'min' => '1900-01-01',
                'max' => date('Y-m-d'),
                'step' => '1',
                )
            )
        );

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

        $filter->add(array(
            'name' => 'birthday',
            'required' => false,
            'validators' => array(
                array('name'    => 'Date',
                    'options' => array (
                        'format' => 'Y-m-d',
                    )
                ),
            ),
        ));

        return $filter;
    }
}
