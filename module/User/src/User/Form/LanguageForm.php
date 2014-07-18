<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\LanguageHydrator;
use \Zend\InputFilter\InputFilter;

/**
 * Class LanguageForm
 *
 * @package User\Form
 */
class LanguageForm extends BaseForm
{
    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('EmailForm');

        $this->setFieldSetService($service);

        $hydrator = new LanguageHydrator();
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
        return $filter;
    }
}

