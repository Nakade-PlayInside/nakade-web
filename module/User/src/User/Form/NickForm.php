<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\NickHydrator;
use \Zend\InputFilter\InputFilter;

/**
 * Class NickForm
 *
 * @package User\Form
 */
class NickForm extends BaseForm
{

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('NickForm');

        $this->setFieldSetService($service);

        $hydrator = new NickHydrator();
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
                'name' => 'nickname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Nick (opt.):'),
                ),
            )
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
                'attributes' => array(
                    'class' => 'checkbox',
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
        $filter->add(array(
            'name' => 'nickname',
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StripNewLines'),
            ),
            'validators'=> array(
                array(
                    'name' => 'StringLength',
                    'options' => array (
                        'encoding' => 'UTF-8',
                        'max' => 20
                    )
                ),
                /*  array(
                      'name'     => 'User\Form\Validator\DBNoRecordExist',
                      'options' => array(
                          'entity'   => 'User\Entity\User',
                          'property' => 'email',
                          'exclude'  => $this->getIdentifierValue(),
                          'adapter'  => $this->getEntityManager(),
                      )
                  )*/
            )
        ));

        return $filter;
    }
}
