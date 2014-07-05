<?php
namespace Season\Form;

use Season\Form\Hydrator\LeagueHydrator;
use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;

class LeagueForm extends BaseForm
{
    private $availablePlayerList = array();
    private $assignedPlayerList = array();

    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('LeagueForm');

        $this->setFieldSetService($service);
        $this->setRepository($repository);

        $hydrator = new LeagueHydrator($this->getRepository()->getEntityManager());
        $this->setHydrator($hydrator);
        $this->setInputFilter($this->getFilter());
    }

    /**
     * @param \Season\Entity\League $object
     */
    public function bindEntity($object)
    {
        $seasonId = $object->getSeason()->getId();
        $playerList = $this->getLeagueMapper()->getAvailableParticipantsBySeason($seasonId);

        /* @var $participant \Season\Entity\Participant */
        foreach ($playerList as $participant) {
            $user = $participant->getUser();
            $this->availablePlayerList[$user->getId()] = $user->getName();
        }

        $assignedList = $this->getLeagueMapper()->getAssignedPlayersByLeague($object->getId());
        foreach ($assignedList as $participant) {
            $user = $participant->getUser();
            $this->assignedPlayerList[$user->getId()] = $user->getName();
        }

        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    /**
     * init
     */
    public function init()
    {
        //association
        $this->add(
            array(
                'name' => 'associationName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Association') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
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
                        'value_options' => $this->getAssignedPlayerList()
                    ),
                    'attributes' => array(
                        'multiple' => 'multiple',
                        'size' => count($this->getAssignedPlayerList())
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
                    'value_options' => $this->getAvailablePlayerList()
                ),
                'attributes' => array(
                    'multiple' => 'multiple',
                    'size' => count($this->getAvailablePlayerList())
                )
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
     * @return array
     */
    public function getAssignedPlayerList()
    {
        return $this->assignedPlayerList;
    }

    /**
     * @return array
     */
    public function getAvailablePlayerList()
    {
        return $this->availablePlayerList;
    }

    /**
     * @return bool
     */
    public function hasAssignedPlayers()
    {
        return !empty($this->assignedPlayerList);
    }
}
