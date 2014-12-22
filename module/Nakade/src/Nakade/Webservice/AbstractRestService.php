<?php

namespace Nakade\Webservice;

use Nakade\Standings\Sorting\PlayerPosition;
use Nakade\Standings\Sorting\SortingInterface;
use Nakade\Standings\MatchStats;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TableStandingsService
 * calculate and provide standings and position of all players in a league
 *
 * @package Nakade\Services
 */
class AbstractRestService
{
    private $url;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Nakade\Services\PlayersTableService
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param array $matches
     * @param string $sort
     * @return array
     */
    public function post()
    {
        //next example will insert new conversation
        $service_url = $this->getUrl();
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'ricerca' => '1',
            'name' => 'Holger',
            'last_name' => 'Maerz',
            'country' => '',
            'club' => '',
            'pin_player' => ''
       //     'filter' => '0'
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }
        echo 'response ok!';
        //echo $curl_response;
        var_export($decoded->response);
       // $test = simplexml_load_string($curl_response);
        var_export($curl_response);

    }

    public function get()
    {
        //next example will recieve all messages for specific conversation
        $service_url = $this->getUrl();
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }
        echo 'response ok!';
        var_export($decoded->response);
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }



}
