<?php
namespace Season\Form;

use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
class LeagueForm extends BaseForm
{

    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('LeagueForm');

        $this->service = $service;
        $this->repository = $repository;
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

        //remove
        if ($this->hasAssignedPlayers()) {
            $this->add(
                array(
                    'name' => 'removePlayer',
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
        }

        //add
        $this->add(
            array(
                'name' => 'addPlayer',
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
     * you have to init this after setting season or league
     */
    protected function prepareForm()
    {

        if (!is_null($this->getSeason())) {
            $this->associationName = $this->getSeason()->getAssociation()->getName();
            $this->seasonNumber = $this->getSeason()->getNumber();
            $this->leagueNumber=$this->getLeagueNumberByRepository($this->getSeason()->getId());
            $this->availablePlayers = $this->getAvailablePlayersByRepository($this->getSeason()->getId());
        } elseif (!is_null($this->getLeague())) {
            $this->season = $this->getLeague()->getSeason();
            $this->associationName = $this->getSeason()->getAssociation()->getName();
            $this->seasonNumber = $this->getSeason()->getNumber();
            $this->leagueNumber = $this->getLeague()->getNumber();
            $this->availablePlayers = $this->getAvailablePlayersByRepository($this->getSeason()->getId());
            $this->assignedPlayers = $this->getAssignedPlayersByRepository($this->getLeague()->getId());
        }

    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                'name' => 'addPlayer',
                'required' => false
            )
        );

        $filter->add(
            array(
                'name' => 'removePlayer',
                'required' => false
            )
        );

        return $filter;
    }

    /**
     * @param int $seasonId
     *
     * @return int|void
     */
    protected function getLeagueNumberByRepository($seasonId)
    {
        /* @var $repository \Season\Mapper\LeagueMapper */
        $repository = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        return $repository->getNewLeagueNoBySeason($seasonId);
    }

}
