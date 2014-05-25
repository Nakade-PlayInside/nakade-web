<?php

namespace Season\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Season\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Season\Form\Hydrator\SeasonHydrator;

/**
 * Class SeasonFormService
 *
 * @package Season\Services
 */
class SeasonFormService extends AbstractFormFactory
{

    const SEASON_FORM = 'season';
    private $seasonfieldSets;



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

       $this->seasonfieldSets = $services->get('Season\Services\SeasonFieldsetService');

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

               $form = new Form\SeasonForm($this->seasonfieldSets);
               $form->setHydrator(new SeasonHydrator($this->getEntityManager()));
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }
        return $form;
    }

}
