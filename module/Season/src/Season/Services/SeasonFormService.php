<?php

namespace Season\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Season\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Season\Form\Hydrator\SeasonHydrator;
use Season\Form\Hydrator\MatchDayHydrator;

/**
 * Class SeasonFormService
 *
 * @package Season\Services
 */
class SeasonFormService extends AbstractFormFactory
{

    const SEASON_FORM = 'season';
    const PARTICIPANT_FORM = 'participant';
    const LEAGUE_FORM = 'league';
    const MATCH_DAY_CONFIG_FORM = 'match_day_config';
    const MATCH_DAY_FORM = 'match_day';
    const DELETE_FORM = 'delete';
    const CREATE_FORM = 'create';

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($this->entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Season']['text_domain']) ?
            $config['Season']['text_domain'] : null;

        $translator = $services->get('translator');
        $repository = $services->get('Season\Services\RepositoryService');
        $fieldSetService = $services->get('Season\Services\SeasonFieldsetService');

        $this->setRepository($repository);
        $this->setFieldSetService($fieldSetService);
        $this->setTranslator($translator, $textDomain);

       return $this;
    }

    /**
     * fabric method for getting the form needed. expecting the form name as
     * string. Throws an exception if provided typ is unknown.
     *
     * @param string $typ
     *
     * @return \Zend\Form\Form
     *
     * @throws \RuntimeException
     */
    public function getForm($typ)
    {
        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');
        $service = $this->getFieldSetService();
        $repository = $this->getRepository();

        switch (strtolower($typ)) {

           case self::SEASON_FORM:
               $extraTime = $mapper->getByoyomi();
               $hydrator = new SeasonHydrator($this->entityManager);
               $form = new Form\SeasonForm($service, $extraTime);
               $form->setHydrator($hydrator);
               break;

           case self::PARTICIPANT_FORM:
               $form = new Form\ParticipantForm($service, $repository);
               break;

           case self::LEAGUE_FORM:
               $form = new Form\LeagueForm($service, $repository);
               break;

           case self::MATCH_DAY_CONFIG_FORM:
               $form = new Form\MatchDayConfigForm($service);
               break;

           case self::MATCH_DAY_FORM:
               $form = new Form\MatchDayForm($service);
               break;

           case self::DELETE_FORM:
               $form = new Form\DeleteForm();
               break;

           case self::CREATE_FORM:
               $form = new Form\CreateForm();
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->setTranslator($this->translator, $this->textDomain);
        return $form;
    }

}
