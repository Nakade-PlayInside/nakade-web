<?php
namespace League\Form;

use User\Entity\User;
use Nakade\Standings\Results;
use Nakade\Abstracts\AbstractForm;
use \League\Form\Hydrator\ResultHydrator;
use Season\Services\SeasonFieldsetService;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Season\Entity\Match;
use \Zend\InputFilter\InputFilter;
/**
 * Form for making a new result
 */
class ResultForm extends AbstractForm
{
    private $service;
    private $resultList;
    private $matchPlayers=array();

    /**
     * @param SeasonFieldsetService $service
     * @param Results               $results
     */
    public function __construct(SeasonFieldsetService $service, Results $results)
    {
        parent::__construct('ResultForm');

        $this->service = $service;
        $this->resultList = $results->getResultTypes();
        $this->setInputFilter($this->getFilter());

    }
    /**
     * @param Match $object
     */
    public function bindEntity(Match $object)
    {
        $this->addPLayer($object->getBlack());
        $this->addPLayer($object->getWhite());
        $this->init();
        $this->bind($object);
    }

    private function addPLayer(User $user)
    {
        $this->matchPlayers[$user->getId()] = $user->getShortName() . ' (' . $user->getOnlineName() .')';
    }

    /**
     * @return SeasonFieldsetService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->setAttribute('method', 'post');

        //pairingId
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'id',
                'options' => array('label' => $this->translate('Match Id') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //winner
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'winnerId',
                'options' => array(
                    'label' => $this->translate('Winner') . ':',
                    'empty_option' => $this->translate('No Winner'),
                    'value_options' => $this->matchPlayers,
                ),
            )
        );

        //result
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'resultId',
                'options' => array(
                    'label' => $this->translate('Result') . ':',
                    'empty_option' => $this->translate('No Result'),
                    'value_options' => $this->resultList,
                ),
            )
        );

         //points
         $this->add(
             array(
                 'name' => 'points',
                 'attributes' => array(
                    'type' => 'text',
                  //  'pattern' => "[12]{0,1}[0-9]{1}([.][5]){0,1}"
                 ),
                 'options'  => array(
                    'label' => $this->translate('Points') . ':'
                 ),
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
                'name'       => 'winnerId',
                'required'   => false,
            )
        );

        $filter->add(
            array(
                'name'       => 'resultId',
                'required'   => true,
            )
        );

        $filter->add(
            array(
                'name'       => 'points',
                'required'   => false,
                'filters'    => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'PregReplace',
                        'options' => array(
                            'pattern' => '/,/',
                            'replacement' => '.',
                        )
                    ),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Float',
                    ),
                ),


            )
        );

        return $filter;
    }
}

