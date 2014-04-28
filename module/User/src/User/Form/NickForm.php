<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Form for nick name changing.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class NickForm extends DefaultForm
{


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

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
        $filter->add($this->getUniqueDbFilter('nickname', null, '20', false));

        return $filter;
    }
}
