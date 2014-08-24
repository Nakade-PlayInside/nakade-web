<?php
namespace Season\Form\Fieldset;

use Nakade\Abstracts\AbstractFieldset;

/**
 * Class ButtonFieldset
 *
 * @package Season\Form\Fieldset
 */
class ButtonFieldset extends AbstractFieldset
{

    /**
     * construct button fieldSet
     */
    public function __construct()
    {
        //form name is SeasonForm
        parent::__construct('button');
    }

    /**
     * init
     */
    public function init()
    {
        //cross-site scripting hash protection
        //this is handled by ZF2 in the background - no need for server-side
        //validation
        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array('csrf_options' => array('timeout' => 600))
            )
        );

        //submit button
        $this->add(
            array(
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => $this->translate('Submit'),
                    'class' => 'btn btn-success actionBtn'
                )
            )
        );

        //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => $this->translate('Cancel'),
                    'class' => 'btn btn-success actionBtn'
                )
            )
        );
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}
