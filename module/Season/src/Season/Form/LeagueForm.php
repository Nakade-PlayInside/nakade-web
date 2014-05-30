<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Entity\Season;
use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
class LeagueForm extends AbstractForm
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
        parent::__construct('LeagueForm');

        $this->service = $service;
        $this->repository = $repository->getMapper('league');
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
                'name' => 'players',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Player roster') . ':',
                    'value_options' => $this->getAcceptedPlayers()
                ),
                'attributes' => array(
                    'multiple' => 'multiple',
                    'size' => count($this->getAcceptedPlayers())
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
     * @return \Season\Mapper\LeagueMapper
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return int
     */
    private function getLeagueNumber()
    {
        $number=0;

        if (!is_null($this->getSeason())) {
            $seasonId = $this->getSeason()->getId();
            $number=$this->getRepository()->getNewLeagueNoBySeason($seasonId);
        }
        return $number;
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
    private function getAcceptedPlayers()
    {
        $list = array();
        if (is_null($this->getSeason())) {
            return $list;
        }

        $seasonId = $this->getSeason()->getId();
        $playerList = $this->getRepository()->getAvailableParticipantsBySeason($seasonId);

        /* @var $player \Season\Entity\Participant */
        foreach ($playerList as $player) {
            $list[$player->getId()] = $player->getUser()->getName();
        }

        return $list;
    }

}
