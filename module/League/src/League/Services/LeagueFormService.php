<?php

namespace League\Services;

use Nakade\Abstracts\AbstractFormFactory;
use League\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use League\Form\Hydrator\ResultHydrator;

/**
 * Class SeasonFormService
 *
 * @package Season\Services
 */
class LeagueFormService extends AbstractFormFactory
{

    const RESULT_FORM = 'result';
    const MATCHDAY_FORM = 'matchday';

    private $resultList;

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

        //configuration
        $textDomain = isset($config['Season']['text_domain']) ?
            $config['Season']['text_domain'] : null;

        $translator = $services->get('translator');

        $repository = $services->get('Season\Services\RepositoryService');
        $fieldSetService = $services->get('Season\Services\SeasonFieldsetService');
        $this->resultList = $services->get('League\Services\ResultService');

        $this->setRepository($repository);
        $this->setFieldSetService($fieldSetService);
        $this->setTranslator($translator);
        $this->setTranslatorTextDomain($textDomain);

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
        switch (strtolower($typ)) {

           case self::RESULT_FORM:
               $service = $this->getFieldSetService();
               $hydrator = new ResultHydrator($this->entityManager);
               $form = new Form\ResultForm($service, $this->resultList);
               $form->setHydrator($hydrator);
               break;

           case self::MATCHDAY_FORM:
               $service = $this->getFieldSetService();
               $hydrator = new Form\Hydrator\MatchDayHydrator($this->entityManager);
               $form = new Form\MatchDayForm($service);
               $form->setHydrator($hydrator);
               $form->init();
               break;


           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        return $form;
    }

}
