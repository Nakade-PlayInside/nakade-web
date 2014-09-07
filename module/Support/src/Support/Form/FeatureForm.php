<?php
namespace Support\Form;

use Support\Form\Hydrator\FeatureHydrator;
use Zend\InputFilter\InputFilter;

/**
 * Class FeatureForm
 *
 * @package Support\Form
 */
class FeatureForm extends BaseForm implements FeatureInterface
{

    public function init()
    {
        $this->add(
            array(
            'name' => self::TYPE,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' =>  $this->translate('Type') . ':',
                'value_options' => $this->getTypeOptions(),
                'empty_option'  => '-- ' . $this->translate('choose') . ' --'
                ),
            )
        );

        $this->add(
            array(
                'name' => self::DESCRIPTION,
                'type' => 'Zend\Form\Element\Textarea',
                'options' => array(
                    'label' =>  $this->translate('Description') . ':',
                ),
            )
        );


        $this->add($this->getButtonFieldSet());
    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                'name' => self::TYPE,
                'required' => true,
                'allowEmpty' => false,
            )
        );

        $filter->add(
            array(
                'name' => self::DESCRIPTION,
                'required' => true,
                'allowEmpty' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                ),
            )
        );

        return $filter;
    }

    /**
     * @return array
     */
    private function getTypeOptions()
    {
        $valueOptions = array(
            'Feature' => $this->translate('Feature'),
            'Bug' => $this->translate('Bug'),
        );

        return $valueOptions;

    }

    /**
     * @return FeatureHydrator
     */
    protected function initHydrator()
    {
        $hydrator = new FeatureHydrator($this->getEntityManager(), $this->getAuthenticationService());
        return $hydrator;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'FeatureForm';
    }
}
