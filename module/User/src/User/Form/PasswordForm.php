<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\PasswordHydrator;
use \Zend\InputFilter\InputFilter;

/**
 * Class PasswordForm
 *
 * @package User\Form
 */
class PasswordForm extends BaseForm
{
    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('PasswordForm');

        $this->setFieldSetService($service);

        $hydrator = new PasswordHydrator();
        $this->setHydrator($hydrator);
        $this->setInputFilter($this->getFilter());
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

         //password
        $this->add(
            array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('enter new password:'),

                ),

            )
        );

        $this->add(
            array(
                'name' => 'repeat',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('repeat new password:'),

                ),

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
        $filter->add($this->getUserFilter(self::FILTER_PASSWORD));
        return $filter;
    }
}

