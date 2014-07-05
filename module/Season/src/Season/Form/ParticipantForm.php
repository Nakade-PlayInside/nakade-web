<?php
namespace Season\Form;

use Season\Form\Hydrator\ParticipantHydrator;
use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
class ParticipantForm extends BaseForm
{
    private $playerList=array();

    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('PlayersForm');

        $this->setFieldSetService($service);
        $this->setRepository($repository);

        $hydrator = new ParticipantHydrator($repository);
        $this->setHydrator($hydrator);
        $this->setInputFilter($this->getFilter());
    }

    /**
     * @param \Season\Entity\Season $object
     */
    public function bindEntity($object)
    {
        $seasonId = $object->getId();
        $playerList = $this->getSeasonMapper()->getAvailablePlayersBySeason($seasonId);

        /* @var $user \User\Entity\User */
        foreach ($playerList as $user) {

            $this->playerList[$user->getId()] = $user->getName();
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
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season no.') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
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
                    'value_options' => $this->getPlayerList()
                ),
                'attributes' => array(
                    'multiple' => 'multiple',
                    'size' => count($this->getPlayerList())
                )
            )
        );


        $this->add($this->getButtonFieldSet());
    }


    /**
     * @return array
     */
    private function getPlayerList()
    {
        return $this->playerList;
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

}
