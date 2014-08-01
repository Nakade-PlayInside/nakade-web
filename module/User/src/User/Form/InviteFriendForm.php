<?php
namespace User\Form;

use User\Entity\Coupon;
use User\Form\Hydrator\CouponHydrator;
use \Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractForm;

/**
 * Class InviteFriendForm
 *
 * @package User\Form
 */
class InviteFriendForm extends AbstractForm
{

    /**
     * @param CouponHydrator $hydrator
     */
     public function __construct(CouponHydrator $hydrator)
     {
        parent::__construct($this->getFormName());
        $this->setHydrator($hydrator);
     }

    /**
     * @param \User\Entity\Coupon $object
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
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array('label' =>  $this->translate('email') . ':'),
                'attributes' => array('multiple' => false)
            )
        );

        //message
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'message',
                'options' => array(
                    'label' =>  $this->translate('Message (opt.)') . ":",
                ),
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array('timeout' => 600)
                )
            )
        );

        //yes
        $this->add(
            array(
                'name' => 'submit',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => $this->translate('Invite NOW!'),
                    'class' => 'btn'
                )
            )
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
        $filter->add(
            array(
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
                )
            )
        );

        $filter->add(
            array(
                'name' => 'message',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                ),
            )
        );
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'InviteFriendForm';
    }

}
