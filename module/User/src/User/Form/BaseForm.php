<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use User\Services\RepositoryService;

/**
 * Class BaseLeagueForm
 *
 * @package Season\Form
 */
abstract class BaseForm extends AbstractForm
{
    protected $fieldSetService;
    protected $repository;

    /**
     * @return SeasonFieldsetService
     */
    public function getFieldSetService()
    {
        return $this->fieldSetService;
    }

    /**
     * @return RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Season\Mapper\LeagueMapper
     */
    public function getUserMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::USER_MAPPER);
    }

    /**
     * @return \Season\Form\Fieldset\ButtonFieldset
     */
    public function getButtonFieldSet()
    {
        return $this->getFieldSetService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getUserMapper()->getEntityManager();
    }

    /**
     * @param RepositoryService $repository
     */
    public function setRepository(RepositoryService $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param SeasonFieldsetService $fieldSetService
     */
    public function setFieldSetService(SeasonFieldsetService $fieldSetService)
    {
        $this->fieldSetService = $fieldSetService;
    }

}
