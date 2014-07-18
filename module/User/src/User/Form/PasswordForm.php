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
        $filter->add(
            array(
                 'name' => 'password',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                 ),
                 'validators' => array(
                     array(
                         'name' => 'StringLength',
                         'options' => array (
                             'encoding' => 'UTF-8',
                             'min' => 6,
                             'max' => 50
                         )
                    ),
                    array('name' => 'Identical',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'token' => 'repeat',
                          )
                     ),
                    array('name' => 'User\Form\Validator\PasswordComplexity',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'length'   => '8',
                              'treshold' => '80',

                          )
                    ),
                    array('name' => 'User\Form\Validator\CommonPassword',
                          'break_chain_on_failure' => true,
                          'options' => array (
                               'commons'  => array(
                                                'password',
                                                '123456',
                                                'qwert',
                                                'abc123',
                                                'letmein',
                                                'myspace',
                                                'monkey',
                                                'iloveyou',
                                                'sunshine',
                                                'trustno1',
                                                'welcome',
                                                'shadow',
                              )
                         )
                    ),

                 )
             )
        );

         return $filter;
    }
}

