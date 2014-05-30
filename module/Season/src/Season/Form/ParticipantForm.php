<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Entity\Season;
use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
class ParticipantForm extends AbstractForm
{

    private $service;
    private $repository;
    private $season;


    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('PlayersForm');

        $this->service = $service;
        $this->repository = $repository->getMapper('participant');
        $this->setInputFilter($this->getFilter());
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
                'name' => 'players',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Available players') . ':',
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

    /**
     * @param Season $season
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }


    /**
     * @return SeasonFieldsetService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return \Season\Mapper\ParticipantMapper
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return int
     */
    private function getSeasonNumber()
    {
        $number=0;
        if (!is_null($this->getSeason())) {
            $number = $this->getSeason()->getNumber();
        }
        return $number;
    }

    /**
     * @return string
     */
    private function getAssociationName()
    {
        $name='';
        if (!is_null($this->getSeason())) {
            $name = $this->getSeason()->getAssociation()->getName();
        }
        return $name;
    }

    /**
     * @return array
     */
    private function getAvailablePlayers()
    {
        $list = array();
        if (is_null($this->getSeason())) {
            return $list;
        }

        $seasonId = $this->getSeason()->getId();
        $playerList = $this->getRepository()->getAvailablePlayersBySeason($seasonId);
        return $this->makePlayerList($playerList);
    }

    /**
     * @return array
     */
    private function getNoInvitedPlayers()
    {
        $list = array();
        if (is_null($this->getSeason())) {
            return $list;
        }

        $seasonId = $this->getSeason()->getId();
        $playerList = $this->getRepository()->getInvitedUsersBySeason($seasonId);

        return count($playerList);
    }

    /**
     * @param array $playerList
     *
     * @return array
     */
    private function makePlayerList(array $playerList)
    {
        $list = array();
        /* @var $player \User\Entity\User */
        foreach ($playerList as $player) {
            $list[] = array(
                'value' => $player->getId(),
                'label' => $player->getName(),
                'selected' => true
            );

        }
        return $list;
    }
}
