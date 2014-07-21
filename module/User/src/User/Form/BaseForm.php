<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use User\Services\RepositoryService;

/**
 * Class BaseLeagueForm
 *
 * @package Season\Form
 */
abstract class BaseForm extends AbstractForm
{
    protected $fieldSetService;
    protected $entityManager;

    /**
     * @return SeasonFieldsetService
     */
    public function getFieldSetService()
    {
        return $this->fieldSetService;
    }

    /**
     * @return \Season\Form\Fieldset\ButtonFieldset
     */
    public function getButtonFieldSet()
    {
        return $this->getFieldSetService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET);
    }

    /**
     * @param SeasonFieldsetService $fieldSetService
     */
    public function setFieldSetService(SeasonFieldsetService $fieldSetService)
    {
        $this->fieldSetService = $fieldSetService;
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
     * add EmailFields
     */
    protected function addEmail()
    {
        //email
        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' =>  $this->translate('email') . ':',
                ),
                'attributes' => array(
                    'multiple' => false,
                    'required' => 'required',
                )
            )
        );
    }

    /**
     * add KgsFields
     */
    protected function addKgs()
    {
        //kgs name
        $this->add(
            array(
                'name' => 'kgs',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('KGS (opt.)') . ':',
                ),
            )
        );
    }

    /**
     * add birthday
     */
    protected function addBirthday()
    {
        //birthday
        $this->add(
            array(
                'name' => 'birthday',
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
            )
        );
    }

    /**
     * add Nick
     */
    protected function addNick()
    {
        //nick name
        $this->add(
            array(
                'name' => 'nickname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Nick (opt.)') . ':',
                ),
            )
        );

        //anonym
        $this->add(
            array(
                'name' => 'anonymous',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('use nick always (anonymous)'),
                    'checked_value' => true,
                ),
                'attributes' => array(
                    'class' => 'checkbox',
                ),
            )
        );
    }

    /**
     * add language
     */
    protected function addLanguage()
    {
        $this->add(
            array(
                'name' => 'language',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('language:'),
                    'value_options' => array(
                        'no_NO' => $this->translate('No language'),
                        'de_DE' => $this->translate('German'),
                        'en_US' => $this->translate('English'),
                    )
                ),
            )
        );
    }

    const FILTER_BIRTHDAY = 'birthday';
    const FILTER_PASSWORD = 'password';

    protected function getUserFilter($name)
    {
        $filter = array();
        switch ($name) {

            case self::FILTER_BIRTHDAY :
                $filter = array(
                    'name' => self::FILTER_BIRTHDAY,
                    'required' => false,
                    'validators' => array(
                        array('name'    => 'Date',
                            'options' => array (
                                'format' => 'Y-m-d',
                            )
                        ),
                    ),
                );
                break;

            case self::FILTER_PASSWORD :
                $filter = array(
                    'name' => self::FILTER_PASSWORD,
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
                        ),
                        array('name' => 'User\Form\Validator\CommonPassword',
                            'break_chain_on_failure' => true,
                        ),

                    )
                );
                break;

        }

        return $filter;

    }

}
