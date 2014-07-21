<?php
namespace User\Form;

use Season\Services\SeasonFieldsetService;
use User\Form\Hydrator\EmailHydrator;
use \Zend\InputFilter\InputFilter;
use Doctrine\ORM\EntityManager;

/**
 * Class EmailForm
 *
 * @package User\Form
 */
class EmailForm extends BaseForm
{

    /**
     * @param SeasonFieldsetService $service
     * @param EntityManager         $entityManager
     */
    public function __construct(SeasonFieldsetService $service, EntityManager $entityManager)
    {
        parent::__construct('EmailForm');

        $this->setFieldSetService($service);
        $this->setEntityManager($entityManager);

        $hydrator = new EmailHydrator();
        $this->setHydrator($hydrator);
       // $this->setInputFilter($this->getFilter());
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->addEmail();
        $this->add($this->getButtonFieldSet());
    }

    /**
     * get the InputFilter
     *
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {

        $filter = new InputFilter();
        $filter->add(
            array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators'=> array(
                    array(
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'min' => 6,
                            'max' => 120
                        )
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                    ),
                   array(
                        'name'     => '\User\Form\Validator\DBNoRecordExist',
                        'options' => array(
                            'entity'   => 'User\Entity\User',
                            'property' => 'email',
                            'excludeId'  => 1,
                            'entityManager'  => $this->getEntityManager(),
                        )
                   )
                )
            )
        );

         return $filter;
    }

}
