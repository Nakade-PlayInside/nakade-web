<?php
namespace Season\Form;


use Nakade\Abstracts\AbstractForm;
use Season\Services\SeasonFieldsetService;
use Season\Services\RepositoryService;

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
     * @return \Season\Services\RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Season\Mapper\LeagueMapper
     */
    public function getLeagueMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

    /**
     * @return \Season\Mapper\SeasonMapper
     */
    public function getSeasonMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
    }

    /**
     * @return \Season\Form\Fieldset\ButtonFieldset
     */
    public function getButtonFieldSet()
    {
        return $this->getFieldSetService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET);
    }

    /**
     * @return \Season\Form\Fieldset\TieBreakerFieldset
     */
    public function getTieBreakerFieldSet()
    {
        return $this->getFieldSetService()->getFieldset(SeasonFieldsetService::TIEBREAKER_FIELD_SET);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getSeasonMapper()->getEntityManager();
    }

    /**
     * @param \Season\Services\RepositoryService $repository
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
