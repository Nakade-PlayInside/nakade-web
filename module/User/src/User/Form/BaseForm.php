<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use User\Form\Factory\UserFieldInterface;
use User\Form\Factory\UserFilterFactory;
use User\Form\Factory\UserFieldFactory;
use User\Form\Hydrator\UserHydrator;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BaseLeagueForm
 *
 * @package Season\Form
 */
abstract class BaseForm extends AbstractForm implements UserFieldInterface
{
    protected $fieldSetService;
    protected $userFieldFactory;
    protected $userFilterFactory;

    /**
     * @param ServiceLocatorInterface $services
     * @param UserHydrator $hydrator
     */
    public function __construct(ServiceLocatorInterface $services, UserHydrator $hydrator)
    {
        parent::__construct($this->getFormName());

        $this->fieldSetService = $services->get('Season\Services\SeasonFieldsetService');
        $this->userFieldFactory = $services->get('User\Services\UserFieldService');
        $this->userFilterFactory = $services->get('User\Services\UserFilterService');

        $this->setHydrator($hydrator);
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
        $this->getUserFilterFactory()->setUser($object);
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    /**
     * @return UserFieldFactory
     */
    public function getUserFieldFactory()
    {
        return $this->userFieldFactory;
    }

    /**
     * @return UserFilterFactory
     */
    public function getUserFilterFactory()
    {
        return $this->userFilterFactory;
    }

    /**
     * @return string
     */
    abstract public function getFormName();

}
