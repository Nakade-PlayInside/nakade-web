<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Form for nick name changing.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class LanguageForm extends DefaultForm
{
    private $language;

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language=$language;
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
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
                'attributes' => array(
                        'value' => $this->language
                )
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
        return $filter;
    }
}

