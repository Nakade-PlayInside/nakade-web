<?php
namespace Moderator\Form;

use Moderator\Form\Hydrator\LeagueManagerHydrator;
use Zend\InputFilter\InputFilter;

/**
 * Class LeagueManagerForm
 *
 * @package Moderator\Form
 */
class LeagueManagerForm extends BaseForm implements ManagerInterface
{

    public function init()
    {
        $this->add(
            array(
            'name' => self::MANAGER,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' =>  $this->translate('Manager') . ':',
                'value_options' => $this->getManagerValueOptions(),
                'empty_option'  => '-- ' . $this->translate('choose') . ' --'
                ),
            )
        );

        $this->add(
            array(
                'name' => self::ASSOCIATION,
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('League') . ':',
                    'value_options' => $this->getAssociationValueOptions(),
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
                'name' => self::MANAGER,
                'required' => true,
                'allowEmpty' => false,
                'validators' => array(
                    array(
                      'name'     => 'Moderator\Form\Validator\ValidLeagueManager',
                      'options' => array(
                            'adapter'  => $this->getEntityManager(),
                        )
                    )
                ),
            )
        );
        return $filter;
    }

    /**
     * @return array
     */
    private function getAssociationValueOptions()
    {
        $valueOptions = array();
        $associations = $this->getMapper()->getAssociationsByUser(1);

        /* @var $association \Season\Entity\Association */
        foreach($associations as $association) {
            $valueOptions[$association->getId()] = $association->getName();
        }

        return $valueOptions;

    }

    /**
     * @return array
     */
    private function getManagerValueOptions()
    {
        $userId = $this->getUserId();

        $valueOptions = array();
        $available = $this->getMapper()->getAvailableManagerByUser($userId);

        /* @var $user \User\Entity\User */
        foreach($available as $user) {
            $valueOptions[$user->getId()] = $user->getName();
        }

        return $valueOptions;

    }

    /**
     * @return int
     */
    protected function getUserId()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return -1;
        }

        return $authService->getIdentity()->getId();
    }



    /**
     * @return LeagueManagerHydrator
     */
    protected function initHydrator()
    {
        $hydrator = new LeagueManagerHydrator($this->getEntityManager(), $this->getAuthenticationService());
        return $hydrator;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'LeagueManagerForm';
    }
}
