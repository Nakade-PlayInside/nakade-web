<?php
namespace Season\Form;

use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;

/**
 * Class EditLeagueForm
 *
 * @package Season\Form
 */
class EditLeagueForm extends BaseLeagueForm
{

    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('EditLeagueForm');

        $this->service = $service;
        $this->repository = $repository->getMapper('league');
        $this->setInputFilter($this->getFilter());
    }

    /**
     * init
     */
    public function init()
    {
        $this->prepareForm();

        //association
        $this->add(
            array(
                'name' => 'associationName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Association') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getAssociationName()
                )
            )
        );

        //number
        $this->add(
            array(
                'name' => 'seasonNumber',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season no.') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getSeasonNumber()
                )
            )
        );

        //number
        $this->add(
            array(
                'name' => 'leagueNumber',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('League no.') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getLeagueNumber()
                )
            )
        );

        //players
        $this->add(
            array(
                'name' => 'assigned',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Remove player') . ':',
                    'value_options' => $this->getAssignedPlayers()
                ),
                'attributes' => array(
                    'multiple' => 'multiple',
                    'size' => count($this->getAssignedPlayers())
                )
            )
        );

        //players
        $this->add(
            array(
                'name' => 'players',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Add player') . ':',
                    'value_options' => $this->getAvailablePlayers()
                ),
                'attributes' => array(
                    'multiple' => 'multiple',
                    'size' => count($this->getAvailablePlayers())
                )
            )
        );


        $this->add($this->getService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET));
    }


    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        return $filter;
    }

}
