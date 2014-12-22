<?php

namespace Nakade\Webservice;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TableStandingsService
 * calculate and provide standings and position of all players in a league
 *
 * @package Nakade\Services
 */
class EGDService implements FactoryInterface
{

    private $rest;
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Nakade\Services\PlayersTableService
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->rest = new AbstractRestService('http://www.europeangodatabase.eu/EGD/Find_Player.php');
        return $this;
    }


    public function findPlayer()
    {
        $this->getRest()->post();
    }

    /**
     * @return \Nakade\Webservice\AbstractRestService
     */
    public function getRest()
    {
        return $this->rest;
    }


}
