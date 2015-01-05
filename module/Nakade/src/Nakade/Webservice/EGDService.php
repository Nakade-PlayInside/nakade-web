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

    const PLAYER_DATA_URL = 'http://www.europeangodatabase.eu/EGD/GetPlayerDataByData.php';
    const PLAYER_PIN_URL = 'http://www.europeangodatabase.eu/EGD/GetPlayerDataByPIN.php';

    private $rest;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Nakade\Services\PlayersTableService
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $this->rest = new AbstractRestService();
        return $this;
    }


    public function findByName($lastName, $name)
    {
        $this->getRest()->setUrl(self::PLAYER_DATA_URL);

        $data = array('lastname' => $lastName, 'name' => $name);
        $result = $this->getRest()->get($data);

        //more than one player
        //no result
        //name == last name while last name == first name
        //export data to EGF Data Model

        if (array_key_exists('players', $result)) {
            var_export($result['players']);
        }

    }

    public function findByPin($pin)
    {
        $this->getRest()->setUrl(self::PLAYER_PIN_URL);

        $data = array('pin' => $pin);
        $result = $this->getRest()->get($data);
        if (array_key_exists('retcode', $result)) {
            var_export($result);
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
