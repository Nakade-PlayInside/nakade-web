<?php
namespace User\Form;

use Permission\Entity\RoleInterface;
use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\UserHydrator;
use \Zend\InputFilter\InputFilter;

/**
 * Class UserForm
 *
 * @package User\Form
 */
class UserForm extends BaseForm implements RoleInterface
{
    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('UserForm');

        $this->setFieldSetService($service);

        $hydrator = new UserHydrator();
        $this->setHydrator($hydrator);
        $this->setInputFilter($this->getFilter());
    }

    /**
     * Init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        $this->addProfile();
        $this->addNick();
        $this->addBirthday();

        //User name
        $this->add(array(
                'name' => 'username',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('User Name') . ':',
                ),
        ));

        $this->addKgs();
        $this->addEmail();

        //role
        $this->add(array(
                'name' => 'role',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Role') . ':',
                    'value_options' => $this->getRoles(),
                ),
                'attributes' => array(
                    'value' => 'user'
                )
        ));

        $this->add($this->getButtonFieldSet());
    }

    /**
     * add Profile
     */
    private function addProfile()
    {
        $this->add(
            array(
                'name' => 'sex',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Salutation') . ':',
                    'value_options' => $this->getSex()
                    ),
            )
        );

        //Title
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Title (opt.)') . ':',
                ),
            )
        );

        //first name
        $this->add(
            array(
                'name' => 'firstName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('First Name') . ':',
                ),
            )
        );

        //family name
        $this->add(
            array(
                'name' => 'lastName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Family Name') . ':',
                ),
            )
        );
    }

    /**
     * @return array
     */
    private function getRoles()
    {
        return array(
            self::ROLE_GUEST  => $this->translate('Guest'),
            self::ROLE_USER   => $this->translate('User'),
            self::ROLE_MEMBER => $this->translate('Member'),
            self::ROLE_MODERATOR => $this->translate('Moderator'),
            self::ROLE_ADMIN  => $this->translate('Administrator'),
        );
    }

    /**
     * @return array
     */
    private function getSex()
    {
        return array(
                'm' => $this->translate('Herr'),
                'f' => $this->translate('Frau'),
        );
    }

    /**
     * get the InputFilter
     *
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
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

