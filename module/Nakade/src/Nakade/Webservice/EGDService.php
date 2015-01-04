<?php

namespace Nakade\Webservice;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EGDService
 *
 * @package Nakade\Webservice
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

        $this->rest = new AbstractRestService('http://www.europeangodatabase.eu/EGD/GetPlayerDataByData.php');
        return $this;
    }


    public function findPlayer()
    {
        $data = array('lastname' => 'Maerz', 'name' => 'Martina');
        $result = $this->getRest()->get($data);

        if (array_key_exists('players', $result)) {
            var_export($result['players']);
        }

    }

    /**
     * @return \Nakade\Webservice\AbstractRestService
     */
    public function getRest()
    {
        return $this->rest;
    }


}
