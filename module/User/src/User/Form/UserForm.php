<?php
namespace User\Form;

use Permission\Entity\RoleInterface;
use \Zend\InputFilter\InputFilter;

/**
 * Class UserForm
 *
 * @package User\Form
 */
class UserForm extends BaseForm implements RoleInterface
{

    /**
     * Init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_SEX));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_TITLE));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_FIRST_NAME));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_LAST_NAME));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_NICK));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_BIRTHDAY));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_USERNAME));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_KGS));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_EMAIL));
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_ROLE));
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


        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_NICK));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_BIRTHDAY));

        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_KGS));
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_EMAIL));

     /*   $this->setPersonFilter('title', '10', false);
        $this->setPersonFilter('firstname', '20');
        $this->setPersonFilter('lastname', '30');

        $this->filter->add($this->getUniqueDbFilter('kgs', null, '50', false));
        $this->filter->add($this->getUniqueDbFilter('nickname', null, '20', false));
        $this->filter->add($this->getUniqueDbFilter('username', null, '20'));
        $this->filter->add($this->getUniqueDbFilter('email', '6', '120'));
        $this->filter->add($this->getBirthday()->getFilter());*/

        return $filter;
    }


    /**
     * @return string
     */
    public function getFormName()
    {
        return 'UserForm';
    }


}

