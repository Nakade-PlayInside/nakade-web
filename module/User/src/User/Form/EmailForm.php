<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\EmailHydrator;
use \Zend\InputFilter\InputFilter;

/**
 * Class EmailForm
 *
 * @package User\Form
 */
class EmailForm extends BaseForm
{

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('EmailForm');

        $this->setFieldSetService($service);

        $hydrator = new EmailHydrator();
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

        //email
        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' =>  $this->translate('email:'),

                ),
                'attributes' => array(
                    'multiple' => false,
                    'required' => 'required',
                )
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
        'name' => 'email',
        'required' => true,
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
                    'min' => 6,
                    'max' => 120
                )
            ),
            array(
            'name' => 'EmailAddress',
            'break_chain_on_failure' => true,
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
