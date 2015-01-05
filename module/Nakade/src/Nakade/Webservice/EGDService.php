<?php

namespace Nakade\Webservice;

use Stats\Entity\EGFData;
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

    /**
     * @param string $lastName
     * @param mixed $firstName
     *
     * @return bool|EGFData
     */
    public function findByName($lastName, $firstName=null)
    {
        $this->getRest()->setUrl(self::PLAYER_DATA_URL);

        $this->convStr($lastName);
        $data = array('lastname' => $this->convStr($lastName));
        if (!empty($firstName)) {
            $data['name'] = $this->convStr($firstName);;
        }

        $result = $this->getRest()->get($data);

        if (array_key_exists('players', $result) && count($result['players']) == 1) {

            $egfData = $result['players'][0];
            return new EGFData($egfData);
        }
        return false;

    }

    /**
     * @param $pin
     *
     * @return bool|EGFData
     */
    public function findByPin($pin)
    {
        $this->getRest()->setUrl(self::PLAYER_PIN_URL);

        $data = array('pin' => $pin);
        $result = $this->getRest()->get($data);
        if (array_key_exists('Pin_Player', $result)) {
            return new EGFData($result);
        }
        return false;
    }


    /**
     * @return \Nakade\Webservice\AbstractRestService
     */
    public function getRest()
    {
        return $this->rest;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function convStr($string)
    {
        return strtr($string, $this->getTransLit());
    }

    /**
     * transliteration array of special chars
     *
     * @return array
     */
    private function getTransLit()
    {
        return array(
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'ae', 'å'=>'a', 'æ'=>'ae',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'Ae', 'Å'=>'A', 'Æ'=>'Ae',

            'Þ'=>'B',
            'þ'=>'b',

            'č'=>'c', 'ć'=>'c', 'ç'=>'c',
            'Č'=>'C', 'Ć'=>'C', 'Ç'=>'C',

            'đ'=>'dj',
            'Đ'=>'Dj',

            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e',
            'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',

            'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I',

            'ñ'=>'n',
            'Ñ'=>'N',

            'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'oe', 'ø'=>'o',
            'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe', 'Ø'=>'O',

            'ð'=>'o',

            'ŕ'=>'r',
            'Ŕ'=>'R',

            'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü' => 'ue',
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü' => 'Ue',

            'š'=>'s',
            'Š'=>'S',
            'ß'=>'ss',

            'ý'=>'y', 'ÿ'=>'y',
            'Ý'=>'Y',

            'ž'=>'z',
            'Ž'=>'Z',


        );
    }


}
