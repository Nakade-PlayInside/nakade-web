<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Entity\Season;
use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
class ParticipantForm extends BaseForm
{

    private $noInvitedPlayers=0;

    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('PlayersForm');

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
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season no.') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getSeasonNumber()
                )
            )
        );

        $this->add(
            array(
                'name' => 'invitedPlayers',
                'type' => 'Zend\Form\Element\text',
                'options' => array('label' =>  $this->translate('No of invited players') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getNoInvitedPlayers()
                )
            )
        );

        //players
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
        return $filter;
    }

    /**
     * @param int $noInvitedPlayers
     */
    public function setNoInvitedPlayers($noInvitedPlayers)
    {
        $this->noInvitedPlayers = $noInvitedPlayers;
    }

    /**
     * @return int
     */
    public function getNoInvitedPlayers()
    {
        return $this->noInvitedPlayers;
    }

    /**
     * you have to init this after setting season or league
     */
    protected function prepareForm()
    {

        if (!is_null($this->getSeason())) {
            $this->associationName = $this->getSeason()->getAssociation()->getName();
            $this->seasonNumber = $this->getSeason()->getNumber();
            $this->availablePlayers = $this->getAvailablePlayersByRepository($this->getSeason()->getId());
            $this->noInvitedPlayers= $this->getNoInvitedPlayersByRepository($this->getSeason()->getId());
        }

    }

    /**
     * @param int $seasonId
     *
     * @return int|void
     */
    protected function getNoInvitedPlayersByRepository($seasonId)
    {
        /* @var $repository \Season\Mapper\ParticipantMapper */
        $repository = $this->getRepository()->getMapper(RepositoryService::PARTICIPANT_MAPPER);;
        $playerList = $repository->getInvitedUsersBySeason($seasonId);
        return count($playerList);
    }


}
