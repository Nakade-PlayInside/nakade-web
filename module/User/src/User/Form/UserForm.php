<?php
namespace User\Form;

use User\Form\Fields\Birthday;
use \Zend\InputFilter\InputFilter;
/**
 * Form for adding or editing a new User.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class UserForm extends DefaultForm
{
    private $birthday;

    /**
     * @return Birthday
     */
    public function getBirthday()
    {
        if (is_null($this->birthday)) {
            $this->birthday=new Birthday($this->getTranslator(), $this->getTranslatorTextDomain());
        }
        return $this->birthday;
    }

    /**
     * Init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {


        $this->add(
            array(
                'name' => 'sex',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Salutation:'),
                    'value_options' => array(
                        'm' => $this->translate('Herr'),
                        'f' => $this->translate('Frau'),
                    )
                ),
            )
        );

        //Title
        $this->add(
            $this->getTextField('title', 'Title (opt.):')
        );

        //first name
        $this->add(
            $this->getTextField('firstname', 'First Name:')

        );

        //family name
        $this->add(
            $this->getTextField('lastname', 'Family Name:')
        );

        //nick name
        $this->add(
            $this->getTextField('nickname', 'Nick (opt.):')

        );

        //anonym
        $this->add(
            array(
                'name' => 'anonym',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('use nick always (anonymizer):'),
                    'checked_value' => true,
                ),
            )
        );


         //birthday
        $this->add($this->getBirthday()->getField());

        //User name
        $this->add(
            $this->getTextField('username', 'User Name:')

        );

        //kgs name
        $this->add(
            $this->getTextField('kgs', 'KGS (opt.):')
        );

        //email
        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' =>  $this->translate('email:'),

                ),
                'attributes' => array(
                    'multiple' => false,
                )
            )
        );

        $this->setRoleFields();
        $this->setDefaultFields();

    }


    private function setRoleFields()
    {
        //roles
        $this->add(
            array(
                'name' => 'role',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Role'),
                    'value_options' => array(
                        'guest'     => $this->translate('Guest'),
                        'user'      => $this->translate('User'),
                        'member'    => $this->translate('Member'),
                        'moderator' => $this->translate('Moderator'),
                        'admin'     => $this->translate('Administrator'),

                    )
                ),
                'attributes' => array(
                    'value' => 'user'
                )

            )


        );
    }


    /**
     * get the InputFilter
     *
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $this->filter = new InputFilter();
        $this->setPersonFilter('title', '10', false);
        $this->setPersonFilter('firstname', '20');
        $this->setPersonFilter('lastname', '30');

        $this->filter->add($this->getUniqueDbFilter('kgs', null, '50', false));
        $this->filter->add($this->getUniqueDbFilter('nickname', null, '20', false));
        $this->filter->add($this->getUniqueDbFilter('username', null, '20'));
        $this->filter->add($this->getUniqueDbFilter('email', '6', '120'));
        $this->filter->add($this->getBirthday()->getFilter());

        return $this->filter;
    }



    private function setPersonFilter($name,$max,$required=true)
    {
        $this->filter->add(
            array(
                'name' => $name,
                'required' => $required,
                'filters'  => $this->getStripFilter(),
                'validators' => array(
                    $this->getStringLengthConfig(null, $max),
                )
            )
        );
    }


}

