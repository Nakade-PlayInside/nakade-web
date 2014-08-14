<?php
namespace Moderator\Form;


use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Moderator\Services\RepositoryService;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class BaseForm
 *
 * @package Moderator\Form
 */
abstract class BaseForm extends AbstractForm
{
    protected $fieldSetService;
    protected $authenticationService;
    protected $mapper;
    protected $recipients;


    /**
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        parent::__construct($this->getFormName());

        $this->mapper = $serviceManager
            ->get('Moderator\Services\RepositoryService')
            ->getMapper(RepositoryService::MANAGER_MAPPER);

        $this->fieldSetService = $serviceManager->get('Season\Services\SeasonFieldsetService');
        $this->entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
        $this->authenticationService = $serviceManager->get('Zend\Authentication\AuthenticationService');

        $hydrator = $this->initHydrator();
        $this->setHydrator($hydrator);
    }

    /**
     * @return HydratorInterface
     */
    abstract protected function initHydrator();

    /**
     * @return SeasonFieldsetService
     */
    public function getFieldSetService()
    {
        return $this->fieldSetService;
    }

    /**
     * @return \Season\Form\Fieldset\ButtonFieldset
     */
    public function getButtonFieldSet()
    {
        return $this->getFieldSetService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET);
    }

    /**
     * @param SeasonFieldsetService $fieldSetService
     */
    public function setFieldSetService(SeasonFieldsetService $fieldSetService)
    {
        $this->fieldSetService = $fieldSetService;
    }

    /**
     * @param \Moderator\Entity\LeagueManager $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    /**
     * @return \Moderator\Mapper\ManagerMapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }


    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return string
     */
    abstract public function getFormName();

}
