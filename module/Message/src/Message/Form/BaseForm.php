<?php
namespace Message\Form;

use Message\Form\Hydrator\MessageHydrator;
use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Message\Services\RepositoryService;

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
            ->get('Message\Services\RepositoryService')
            ->getMapper(RepositoryService::MESSAGE_MAPPER);

        $this->fieldSetService = $serviceManager->get('Season\Services\SeasonFieldsetService');
        $this->entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
        $this->authenticationService = $serviceManager->get('Zend\Authentication\AuthenticationService');

        $messageHydrator = new MessageHydrator($this->getEntityManager(), $this->getAuthenticationService());
        $this->setHydrator($messageHydrator);
    }

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
     * @param \User\Entity\User $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    /**
     * @return \Message\Mapper\MessageMapper
     */
    public function getMessageMapper()
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
     * @return int
     */
    public function getSenderId()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return null;
        }

        return $authService->getIdentity()->getId();
    }

    /**
     * @return bool
     */
    protected function hasRecipients()
    {
        $recipients = $this->getRecipients();
        return !empty($recipients);
    }

    /**
     * @return array
     */
    protected function getRecipients()
    {
        if (is_null($this->recipients)) {
            $senderId = $this->getSenderId();
            $this->recipients = $this->getMessageMapper()->getAllRecipients($senderId);
        }
        return $this->recipients;
    }


    /**
     * @return string
     */
    abstract public function getFormName();

}
