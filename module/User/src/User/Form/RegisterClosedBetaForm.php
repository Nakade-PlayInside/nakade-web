<?php
namespace User\Form;

use Permission\Entity\RoleInterface;
use \Zend\InputFilter\InputFilter;

/**
 * Class RegisterClosedBetaForm
 *
 * @package User\Form
 */
class RegisterClosedBetaForm extends BaseForm implements RoleInterface
{


    /**
     * Init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_SEX));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_FIRST_NAME));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_LAST_NAME));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_USERNAME));

        $this->add(
                array(
                'name' => self::FIELD_EMAIL,
                'type' => 'Zend\Form\Element\Email',
                'options' => array('label' =>  $this->translate('email') . ':'),
                'attributes' => array(
                    'multiple' => false,
                    'readonly' => true
                )
            )
        );

        $this->add($this->getUserFieldFactory()->getField(self::FIELD_KGS));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_CODE));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_AGREEMENT));


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

        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_FIRST_NAME));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_LAST_NAME));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_USERNAME));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_EMAIL));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_KGS));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_CODE));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_AGREEMENT));

        return $filter;
    }


    /**
     * @return string
     */
    public function getFormName()
    {
        return 'RegistrationForm';
    }


}

