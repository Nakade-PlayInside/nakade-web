<?php
namespace Moderator\Form;

use Moderator\Form\Hydrator\RefereeHydrator;
use Zend\InputFilter\InputFilter;

/**
 * Class RefereeForm
 *
 * @package Moderator\Form
 */
class RefereeForm extends BaseForm implements ManagerInterface
{

    public function init()
    {
        $this->add(
            array(
            'name' => self::USER,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' =>  $this->translate('Available member') . ':',
                'value_options' => $this->getMemberValueOptions(),
                'empty_option'  => '-- ' . $this->translate('choose') . ' --'
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
                'name' => self::USER,
                'required' => true,
                'allowEmpty' => false,
            )
        );
        return $filter;
    }

    /**
     * @return array
     */
    private function getMemberValueOptions()
    {
        $valueOptions = array();
        $available = $this->getMapper()->getAvailableReferee();

        /* @var $user \User\Entity\User */
        foreach($available as $user) {
            $valueOptions[$user->getId()] = $user->getName();
        }

        return $valueOptions;

    }

    /**
     * @return RefereeHydrator
     */
    protected function initHydrator()
    {
        $hydrator = new RefereeHydrator($this->getEntityManager());
        return $hydrator;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'RefereeForm';
    }
}
