<?php

namespace Nakade\Webservice;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Receive data from external websites oder services. You have to provide the url, data needed in correct naming and
 * form, to get a proper result. For GET request, you will setup an AJAX request by default and get JSON result as an
 * array by default. Otherwise, you receive a DOM result.
 *
 * @package Nakade\Webservice
 */
class AbstractRestService
{
    private $url;
    private $curl;
    private $response;

    public function setUrl($url)
    {
        $this->url = $url;
         return $this;
    }

    /**
     * @param array $data
     *
     * @return \DOMDocument
     */
    public function post(array $data)
    {
        $this->init()->setPostData($data);
        $this->exec();

        return $this->getDom();

    }

    /**
     * Get REST by GET request. By default return value is Json as an array. If option is set true,
     * the DOM is returned.
     *
     * @param array $param
     * @param bool $isDOM
     *
     * @return array|\DOMDocument|null
     */
    public function get(array $param, $isDOM=false)
    {
        $this->setQuery($param)->init();
        $this->exec();

        if ($isDOM) {
            return $this->getDom();
        }

        return $this->getJson();
    }


    /**
     * init a cUrl session
     *
     * @return $this
     */
    private function init()
    {
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return $this->setCurl($curl);
    }

    /**
     * get request query
     *
     * @param array $param
     *
     * @return $this
     */
    private function setQuery(array $param)
    {
        if (!empty($param)) {
            $query = http_build_query($param);
            $this->url .= '?' . $query;
        }
        return $this;
    }

    /**
     * post request data
     *
     * @param array $data
     *
     * @return $this
     */
    private function setPostData(array $data)
    {
        $curl = $this->curl;
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        return $this->setCurl($curl);
    }

    /**
     * execute service url
     *
     * @return $this
     */
    private function exec()
    {
        $this->response = curl_exec($this->curl);
        curl_close($this->curl);

        return $this;
    }

    /**
     * get json as array or null
     *
     * @return null|array
     */
    private function getJson()
    {
        $json = json_decode($this->response, true);
        return $json;
    }

    /**
     * @return \DOMDocument
     */
    private function getDom()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML($this->response);

        return $dom;
    }


    /**
     * set a cUrl resource
     *
     * @param $curl
     *
     * @return $this
     */
    private function setCurl($curl)
    {
        $this->curl = $curl;
        return $this;
    }


}
