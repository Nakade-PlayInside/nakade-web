<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Entity\Season;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
class ParticipantForm extends AbstractForm
{

    private $players;
    private $service;
    private $season;
    private $lastSeasonPlayers;


    /**
     * @param SeasonFieldsetService $service
     * @param array                 $byoyomi
     */
    public function __construct(SeasonFieldsetService $service, array $players)
    {
        parent::__construct('PlayersForm');

        $this->service = $service;
        $this->players = $this->getPlayerList($players);
         //    $this->setInputFilter($this->getFilter());
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
                    'value' => $this->getSeason()->getAssociation()->getName()
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
                    'value' => $this->getSeason()->getNumber()
                )
            )
        );


        //players
        $this->add(
            array(
                'name' => 'players',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Invite players') . ':',
                    'value_options' => $this->players
                ),
                'attributes' => array(
                    'multiple' => 'multiple',
                    'size' => count($this->players)
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

        $filter->add($this->getValidation('baseTime'));
        $filter->add($this->getValidation('additionalTime'));
        $filter->add($this->getValidation('period'));
        $filter->add($this->getValidation('moves'));
        $filter->add($this->getValidation('komi', 'Float'));


        return $filter;
    }

    /**
     * @param string $name
     * @param string $validation
     *
     * @return array
     */
    private function getValidation($name, $validation='Digits')
    {

        return array(
            'name' => $name,
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StripNewLines'),
            ),
            'validators' => array(
                array('name'    => $validation),
            )
        );
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
     * @param array $players
     *
     * @return array
     */
    private function getPlayerList(array $players)
    {
        $list = array();

        /* @var $player \User\Entity\User */
        foreach ($players as $player) {
            $list[] = array(
                'value' => $player->getId(),
                'label' => $player->getName(),
                'selected' => true
            );

        }
        $list[0]['selected']=false;
        return $list;
    }
}
