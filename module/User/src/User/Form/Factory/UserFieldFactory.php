<?php
namespace User\Form\Factory;

use Nakade\Abstracts\AbstractTranslation;
use Permission\Entity\RoleInterface;

class UserFieldFactory extends AbstractTranslation
    implements UserFieldInterface, RoleInterface, LanguageInterface, SexInterface
{

    //todo: refactor for field sets using hydrators and filter

    /**
     * @param string $field
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getField($field)
    {
        switch($field) {

            case self::FIELD_KGS :
                $field = array(
                    'name' => self::FIELD_KGS,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('KGS (opt.)') . ':',)
                );
                break;

            case self::FIELD_EMAIL :
                $field = array(
                    'name' => self::FIELD_EMAIL,
                    'type' => 'Zend\Form\Element\Email',
                    'options' => array('label' =>  $this->translate('email') . ':'),
                    'attributes' => array('multiple' => false)
                );
                break;

            case self::FIELD_NICK:
                $field = array(
                    'name' => self::FIELD_NICK,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('Nick (opt.)') . ':'),
                );
                break;

            case self::FIELD_ANONYMOUS :
                $field = array(
                    'name' => self::FIELD_ANONYMOUS,
                    'type' => 'Zend\Form\Element\Checkbox',
                    'options' => array(
                        'label' =>  $this->translate('use nick always (anonymous)'),
                        'checked_value' => true,
                    ),
                    'attributes' => array('class' => 'checkbox'),
                );
                break;

            case self::FIELD_BIRTHDAY :
                $field = array(
                    'name' => self::FIELD_BIRTHDAY,
                    'type' => 'Zend\Form\Element\Date',
                    'options' => array(
                        'label' =>  $this->translate('Birthday') . ':',
                        'format' => 'Y-m-d',
                    ),
                    'attributes' => array(
                        'min' => '1900-01-01',
                        'max' => date('Y-m-d'),
                        'step' => '1',
                    )
                );
                break;

            case self::FIELD_LANGUAGE :
                $field = array(
                    'name' => self::FIELD_LANGUAGE,
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' =>  $this->translate('language:'),
                        'value_options' => $this->getLanguages()
                    )
                );
                break;

            case self::FIELD_ROLE :
                $field = array(
                    'name' => self::FIELD_ROLE,
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => $this->translate('Role') . ':',
                        'value_options' => $this->getRoles(),
                    ),
                    'attributes' => array('value' => self::ROLE_USER)
                );
                break;

            case self::FIELD_USERNAME :
                $field = array(
                    'name' => self::FIELD_USERNAME,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('User Name') . ':'),
                );
                break;

            case self::FIELD_SEX :
                $field = array(
                    'name' => self::FIELD_SEX,
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' =>  $this->translate('Salutation') . ':',
                        'value_options' => $this->getSex()
                    ),
                    'attributes' => array('value' => self::SEX_GENTLEMAN)
                );
                break;

            case self::FIELD_FIRST_NAME :
                $field = array(
                    'name' => self::FIELD_FIRST_NAME,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('First Name') . ':')
                );
                break;

            case self::FIELD_LAST_NAME :
                $field = array(
                    'name' => self::FIELD_LAST_NAME,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('Family Name') . ':'),
                );
                break;

            case self::FIELD_TITLE :
                $field = array(
                    'name' => self::FIELD_TITLE,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('Title') . ':'),
                );
                break;

            case self::FIELD_PWD :
                $field = array(
                    'name' => self::FIELD_PWD,
                    'type' => 'Zend\Form\Element\Password',
                    'options' => array('label' =>  $this->translate('enter new password') . ':'),
                );
                break;

            case self::FIELD_PWD_REPEAT :
                $field = array(
                    'name' => self::FIELD_PWD_REPEAT,
                    'type' => 'Zend\Form\Element\Password',
                    'options' => array('label' =>  $this->translate('repeat new password') . ':')
                );
                break;

            case self::FIELD_CODE :
                $field = array(
                    'name' => self::FIELD_CODE,
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array('label' =>  $this->translate('Coupon Code') . ':'),
                );
                break;
        }

        return $field;

    }

    /**
     * @return array
     */
    private function getLanguages()
    {
        return array(
            self::LANG_NO => $this->translate('No language'),
            self::LANG_DE => $this->translate('German'),
            self::LANG_US => $this->translate('English'),
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
            self::SEX_GENTLEMAN => $this->translate('Herr'),
            self::SEX_LADY => $this->translate('Frau'),
        );
    }

}
