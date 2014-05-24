<?php

namespace Season\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Season\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class SeasonFormService extends AbstractFormFactory
{

    const SEASON_FORM = 'season';
    private $repository;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');


        //configuration
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;

        $translator = $services->get('translator');

        $this->repository = $services->get('Season\Services\RepositoryService');

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

           case self::SEASON_FORM:

               /* @var $mapper \Season\Mapper\SeasonMapper */
               $mapper = $this->repository->getMapper('season');
               $tieBreaker = $mapper->getTieBreaker();
               $last = $mapper->getLastSeasonByAssociation(1);
               $form = new Form\SeasonForm($tieBreaker, $last);
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->setTranslator($this->getTranslator());
        $form->setTranslatorTextDomain($this->getTranslatorTextDomain());
        return $form;
    }


}
