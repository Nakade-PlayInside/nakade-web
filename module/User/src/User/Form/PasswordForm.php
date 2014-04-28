<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Form for password changing.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class PasswordForm extends DefaultForm
{

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
        $filter->add(
            array(
                 'name' => 'password',
                 'required' => true,
                 'filters'  => $this->getStripFilter(),
                 'validators' => array(
                     $this->getStringLengthConfig(6, 50),
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

